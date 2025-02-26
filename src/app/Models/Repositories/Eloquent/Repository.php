<?php

declare(strict_types=1);

namespace App\Models\Repositories\Eloquent;

use Throwable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model as Entity;
use App\Models\Repositories\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\File;

abstract class Repository implements RepositoryInterface
{
    /**
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected static $model;

    /**
     *
     * @var array
     */

    /**
     * Eager Loading
     *
     * @var array
     */
    protected static $eagerLoadings = [];

    /**
     *
     * @param  array  $data
     * @return Entity
     */
    public function new(array $data): Entity
    {
        return new static::$model($data);
    }

    /**
     *
     * @param  int  $id
     * @return Entity
     */
    public function find($id): ?Entity
    {
        return (static::$model)::find($id);
    }

    /**
     *
     * @param  int  $id
     * @return bool
     */
    public function exist($id)
    {
        $entity = (static::$model)::find($id);
        return $entity !== null;
    }

    /**
     *
     * @param  int  $id
     * @return Entity
     */
    public function findOnlyTrashed($id): Entity
    {
        $query = (static::$model)::query();
        $fillable = $query->getModel()->getFillable();

        if (in_array('is_deleted', $fillable)) {
            $query->where('is_deleted', '=', true);
        }
        return $query->findOrFail($id);
    }

    /**
     *
     * @param  int  $id
     * @return Entity
     */
    public function findWithTrashed($id): Entity
    {
        return (static::$model)::findOrFail($id);
    }

    /**
     *
     * @param  Entity  $entity
     * @param  array   $data
     * @return Entity
     */
    public function edit(Entity $entity, array $data): Entity
    {
        $entity->fill($data);

        return $entity;
    }

    /**
     *
     * @param  Entity  $entity
     * @return Entity
     * @throws Throwable
     */
    public function persist(Entity $entity): Entity
    {
        try {
            //return DB::connection(app(static::$model)->getConnectionName())->transaction(function () use ($entity) {
            return DB::transaction(function () use ($entity) {
                $entity->saveOrFail();

                return $entity;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     *
     * @param  Entity  $entity
     * @return bool|null
     * @throws Throwable
     */
    public function delete(Entity $entity)
    {
        $query = (static::$model)::query();
        $fillable = $query->getModel()->getFillable();

        try {
            //return DB::connection(app(static::$model)->getConnectionName())->transaction(function () use ($entity) {
            return DB::transaction(function () use ($entity, $fillable) {
                if (in_array('is_deleted', $fillable)) {
                    $entity->is_deleted = true;
                    $entity->saveOrFail();
                    return $entity;
                } else {
                    $result = $entity->delete();
                    return $result;
                }
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
    /**
     *
     * @param  Collection  $entity
     * @return bool|null
     * @throws Throwable
     */

    public function deleteCollection(Collection $entities)
    {
        try {
            return DB::transaction(function () use ($entities) {
                $entities->each(function ($entity) {
                    $entity->delete();
                });
                return true;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     *
     * @param  Entity  $entity
     * @return bool|null
     * @throws Throwable
     */
    public function restore(Entity $entity)
    {
        $query = (static::$model)::query();
        $fillable = $query->getModel()->getFillable();

        try {
            return DB::transaction(function () use ($entity, $fillable) {
                if (in_array('is_deleted', $fillable)) {
                    $entity->is_deleted = false;
                    $entity->saveOrFail();
                    return $entity;
                } else {
                    $result = $entity->restore();
                    return $entity;
                }
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     *
     * @param  array  $conditions
     * @return int
     */
    public function count(array $conditions = []): int
    {
        $query = (static::$model)::query();

        $table = $query->getModel()->getTable();

        $query->selectRaw("COUNT(`{$table}`.`id`) as `count`");
        $this->buildWhereClauseByConditions($query, $conditions);

        return $query->count();
    }

    /**
     *
     * @param  array  $conditions
     * @param  int    $limit
     * @param  int    $offset
     * @return Collection
     */
    public function list(array $conditions = [], $limit = null, $offset = null, $use_eager = true): Collection
    {
        $query = (static::$model)::query();

        if (0 < count(static::$eagerLoadings) && $use_eager) {
            $query->with(static::$eagerLoadings);
        }

        $this->buildSelectClauseByConditions($query, $conditions);
        $this->buildJoinClauseByConditions($query, $conditions);
        $this->buildWhereClauseByConditions($query, $conditions);
        $this->buildOrderbyClauseByConditions($query, $conditions);

        if (null != $limit) {
            $query->limit($limit);
        }
        if (null != $offset) {
            $query->offset($offset);
        }
        if (array_key_exists('is_debug', $conditions)) {
            dd($query->toRawSql());
        }

        $collection  = $query->get();

        return collect($collection)->map(function (Entity $entity) {
            return $entity;
        });
    }

    /**
     *
     * @param  array  $conditions
     * @param  int    $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $conditions = [], int $perPage = 10, $use_eager = true): LengthAwarePaginator
    {
        $query = (static::$model)::query();


        if (0 < count(static::$eagerLoadings) && $use_eager) {
            $query->with(static::$eagerLoadings);
        }
        $this->buildSelectClauseByConditions($query, $conditions);
        $this->buildJoinClauseByConditions($query, $conditions);
        $this->buildWhereClauseByConditions($query, $conditions);
        $this->buildOrderbyClauseByConditions($query, $conditions);
        if (array_key_exists('is_debug', $conditions)) {
            dd($query->toRawSql());
        }
        $paginator  = $query->paginate($perPage);
        $collection = $paginator->getCollection();

        return $paginator->setCollection(collect($collection)->map(function (Entity $entity) {
            return $entity;
        }));
    }

    /**
     *
     * @param  Builder  $queryBuilder
     * @param  array    $conditions
     * @return void
     */
    protected function buildSelectClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        if (array_key_exists('raw_select_columns', $conditions)) {
            $raw_select_columns = null === $conditions['raw_select_columns'] || '' === $conditions['raw_select_columns'] ? null : (string) $conditions['raw_select_columns'];
            if (null !== $raw_select_columns) {
                $queryBuilder->selectRaw($raw_select_columns);
            }
        }
    }

    /**
     *
     * @param  Builder  $queryBuilder
     * @param  array    $conditions
     * @return void
     */
    protected function buildJoinClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        if (array_key_exists('join_tables', $conditions)) {
            foreach ($conditions['join_tables'] as $join_table) {
                if ($join_table['type'] == 'LEFT') {
                    $queryBuilder->leftJoin($join_table['table'], $join_table['first'], '=', $join_table['second']);
                } else {
                    $queryBuilder->join($join_table['table'], $join_table['first'], '=', $join_table['second']);
                }
            }
        }
    }

    /**
     *
     * @param  Builder  $queryBuilder
     * @param  array    $conditions
     * @return void
     */
    protected function buildWhereClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('id', $conditions)) {
            $id = null === $conditions['id'] || '' === $conditions['id'] ? null : (int) $conditions['id'];
            if (null !== $id) {
                $queryBuilder->where("{$table}.id", '=', $id);
            }
        }

        if (array_key_exists('ids', $conditions)) {
            $ids = null === $conditions['ids'] || '' === $conditions['ids']
                ? null
                : (is_json($conditions['ids']) ? (array) json_decode($conditions['ids']) : (array) $conditions['ids']);
            if (is_array($ids) && 0 < count($ids)) {
                $ids = array_map('intval', $ids);
                $queryBuilder->whereIn("{$table}.id", $ids);
            }
        }

        if (array_key_exists('ignore_ids', $conditions)) {
            $ignoreIds = null === $conditions['ignore_ids'] || '' === $conditions['ignore_ids']
                ? null
                : (is_json($conditions['ignore_ids']) ? (array) json_decode($conditions['ignore_ids']) : (array) $conditions['ignore_ids']);
            if (is_array($ignoreIds) && 0 < count($ignoreIds)) {
                $ignoreIds = array_map('intval', $ignoreIds);
                $queryBuilder->whereNotIn("{$table}.id", $ignoreIds);
            }
        }
    }

    /**
     *
     * @param  Builder  $queryBuilder
     * @param  array    $conditions
     * @return void
     */
    protected function buildOrderbyClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        $model = $queryBuilder->getModel();
        $table = $model->getTable();
        $primary_key = $model->getKeyName();
        $fillable = $model->getFillable();

        if (array_key_exists('orders', $conditions) && is_array($conditions['orders'])) {
            foreach ($conditions['orders'] as $orderby => $order) {
                if ('random' == $orderby) {
                    $queryBuilder->inRandomOrder();
                } else {
                    $queryBuilder->orderBy($orderby, $order);
                }
            }
        } elseif (array_key_exists('orderby', $conditions)) {
            $orderby = null === $conditions['orderby'] || '' === $conditions['orderby'] ? null : (string) $conditions['orderby'];
            $order   = isset($conditions['order']) && null !== $conditions['order'] && '' !== $conditions['order'] ? (string) $conditions['order'] : 'ASC';
            $queryBuilder->orderBy($orderby, $order);
        } elseif (array_key_exists('orderByRaws', $conditions)) {
            foreach ($conditions['orderByRaws'] as $orderby => $order) {
                if ('' == $order) {
                    $queryBuilder->orderByRaw($orderby);
                } else {
                    $queryBuilder->orderBy($orderby, $order);
                }
            }
        } else {
            if (in_array('display_order', $fillable)) {
                $queryBuilder->orderBy("{$table}.display_order", 'ASC');
            }
            if (is_array($primary_key)) {
                foreach ($primary_key as $key) {
                    $queryBuilder->orderBy("{$table}.{$key}", 'ASC');
                }
            } elseif (is_string($primary_key) && '' !== $primary_key) {
                $queryBuilder->orderBy("{$table}.{$primary_key}", 'ASC');
            }
        }
    }

    /**
     *
     * @param  Builder  $queryBuilder
     * @param  array    $conditions
     * @return void
     */
    protected function buildGroupByClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('groups', $conditions) && is_array($conditions['groups'])) {
            $queryBuilder->groupBy($conditions['groups']);
        }
    }
    public function sqlList($domain, $name,  array $parameters = [], array $conditions = [], array $orderBy = [], array $paginate = []): Collection
    {
        $is_debug = false;
        $patterns = [
            "string"    => "'%s'",
            "double"    => "%f",
            "integer"   => "%d",
            'boolean'   => "%s",
        ];
        if (array_key_exists('is_debug', $parameters)) {
            $is_debug = $parameters['is_debug'] ?? false;
            unset($parameters['is_debug']);
            if ($is_debug) {
                DB::enableQueryLog();
            }
        }
        $filename =  $domain . '/' . $name . '.sql';
        $filePath = app_path('/Models/Repositories/SqlFiles/' . $filename);
        if (!File::exists($filePath)) {
            abort(500, json_encode(['error' => 'SQL file not found.']));
        }

        $sql = file_get_contents($filePath);

        if (empty($sql)) {
            abort(500, json_encode(['error' => 'SQL file not found.']));
        }
        preg_match_all('/@([a-zA-Z0-9_]+)/', $sql, $matches);
        $sql = str_replace('%', '%%', $sql);
        $variableNames = $matches[1];
        $params = [];

        foreach ($variableNames as $variableName) {
            if (array_key_exists($variableName, $parameters)) {
                $params[] = $parameters[$variableName];
                $typeOf = gettype($parameters[$variableName]);
                $sql = preg_replace('@' . preg_quote('@' . $variableName, '@') . '@', $patterns[$typeOf], $sql, 1);
            }
        }
        $whereCondition = "WHERE 1=1 ";
        if (array_key_exists('raw_conditions', $conditions)) {
            if (is_array($conditions['raw_conditions'])) {
                $rawConditions = $conditions['raw_conditions'];
                foreach ($rawConditions as $rawCondition) {
                    $whereCondition .= "\n" . $rawCondition;
                }
            } else {
                $whereCondition = $conditions['raw_conditions'];
            }
        } else {
            foreach ($conditions as $key => $value) {
                if ($value !== null) {
                    if (is_array($value)) {
                        $placeholders = implode(', ', array_map(function ($i) use ($key) {
                            return ":{$key}_{$i}";
                        }, array_keys($value)));
                        $whereCondition .= " AND $key IN ($placeholders)";
                    } else {
                        $whereCondition .= " AND $key = :$key ";
                    }
                    if (!array_key_exists($key, $params)) {
                        if (is_array($value)) {
                            foreach ($value as $index => $val) {
                                $params["{$key}_{$index}"] = $val;
                            }
                        } else {
                            $params[$key] = $value;
                        }
                    }
                }
            }
        }
        if (!empty($orderBy)) {
            $orderBys = [];
            foreach ($orderBy as $key => $value) {
                $orderBys[] = $key . ' ' . $value;
            }
            $orderByQuery = 'ORDER BY ' . implode(',', $orderBys);
            $sql = str_replace('/**orderby**/', $orderByQuery, $sql);
        }
        $sql = str_replace('/**where**/', $whereCondition, $sql);
        if (!empty($paginate)) {
            $perPage = array_key_exists('page_size', $paginate) ? $paginate['page_size'] : 100;
            $page = array_key_exists('page', $paginate) ? $paginate['page'] : 1;
            $sql .= " LIMIT $perPage OFFSET " . ($page - 1) * $perPage;
        }
        $rawSql = vsprintf($sql, $params);

        if ($is_debug) {
            DB::disableQueryLog();
            dd($rawSql, $sql, $params);
        }
        $results = DB::select($rawSql);
        if ($is_debug) {
            DB::disableQueryLog();
        }
        return collect($results)->map(function ($entity) {
            return $entity;
        });
    }
}

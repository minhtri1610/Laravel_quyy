<?php
declare(strict_types=1);

namespace App\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\User;
use App\Models\Repositories\Contracts\UserServiceRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class UserServiceRepository extends Repository implements UserServiceRepositoryInterface
{
    protected static $model = User::class;

    protected function buildWhereClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        parent::buildWhereClauseByConditions($queryBuilder, $conditions);

        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('nick_name', $conditions)) {
            $nick_name = null === $conditions['nick_name'] || '' === $conditions['nick_name'] ? null : $conditions['nick_name'];
            if (null !== $nick_name) {
                $queryBuilder->where("{$table}.nickname",'=',$nick_name);
            }
        }

        if (array_key_exists('uid', $conditions)) {
            $uid = null === $conditions['uid'] || '' === $conditions['uid'] ? null : $conditions['uid'];
            if (null !== $uid) {
                $queryBuilder->where("{$table}.uid",'=',$uid);
            }
        }

        if (isset($conditions['search']) && $conditions['search'] !== '') {
            $searchTerm = $conditions['search'];
            $queryBuilder->where(function ($query) use ($table, $searchTerm) {
                $query->orWhere("{$table}.name", 'like', "%{$searchTerm}%")
                      ->orWhere("{$table}.nickname", 'like', "%{$searchTerm}%")
                      ->orWhere("{$table}.phone", 'like', "%{$searchTerm}%");
            });
        }

        if (isset($conditions['keys']) && $conditions['keys'] !== '') {
            $searchTerm = $conditions['keys'];
            $queryBuilder->where(function ($query) use ($table, $searchTerm) {
                $query->orWhere("{$table}.name", 'like', "%{$searchTerm}%")
                        ->orWhere("{$table}.uid_code", 'like', "%{$searchTerm}%")
                        ->orWhere("{$table}.nickname", 'like', "%{$searchTerm}%")
                        ->orWhere("{$table}.phone", 'like', "%{$searchTerm}%");
            });
        }

        if(isset($conditions['year']) && $conditions['year'] !== null){
            $year = $conditions['year'];
            $queryBuilder->whereYear("{$table}.date_registered", $year);
        }

        if(isset($conditions['no_nickname']) && $conditions['no_nickname'] !== null){
            $queryBuilder->whereNull("{$table}.nickname");
        }
    }
    public function getLatest(){
        return (static::$model)::orderBy('id', 'desc')->first();
    }

    public function findByUid($uid){
        return (static::$model)::where('uid', $uid)->first();
    }
}

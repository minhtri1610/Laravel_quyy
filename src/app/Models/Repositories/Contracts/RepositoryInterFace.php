<?php
declare(strict_types=1);

namespace App\Models\Repositories\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model as Entity;

interface RepositoryInterface
{
    /**
     *
     * @param  array  $data
     * @return Entity
     */
    public function new(array $data): Entity;

    /**
     *
     * @param  int  $id
     * @return Entity
     */
    public function find(int $id): ?Entity;

    /**
     *
     * @param  Entity  $entity
     * @param  array   $data
     * @return Entity
     */

    /**
     *
     * @param  int  $id
     * @return bool
     */
    public function exist($id);

    public function edit(Entity $entity, array $data): Entity;

    /**
     *
     * @param  Entity  $entity
     * @return Entity
     */
    public function persist(Entity $entity): Entity;

    /**
     *
     * @param  Entity  $entity
     * @return bool|null
     */
    public function delete(Entity $entity);

    /**
     *
     * @param  Collection  $entity
     * @return bool|null
     * @throws Throwable
     */

     public function deleteCollection(Collection $entities);

    /**
     *
     * @param  array  $conditions
     * @return int
     */
    public function count(array $conditions = []): int;

    /**
     *
     * @param  array  $conditions
     * @param  int    $limit
     * @param  int    $offset
     * @return Collection
     */
    public function list(array $conditions = [], $limit = null, $offset = null): Collection;

    /**
     *
     * @param  array  $conditions
     * @param  int    $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $conditions = [], int $perPage): LengthAwarePaginator;
}

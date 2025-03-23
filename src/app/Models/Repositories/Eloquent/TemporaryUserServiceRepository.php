<?php
declare(strict_types=1);

namespace App\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\TemporaryUser;
use App\Models\Repositories\Contracts\TemporaryUserServiceRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class TemporaryUserServiceRepository extends Repository implements TemporaryUserServiceRepositoryInterface
{
    protected static $model = TemporaryUser::class;

    protected function buildWhereClauseByConditions(Builder &$queryBuilder, array $conditions = [])
    {
        parent::buildWhereClauseByConditions($queryBuilder, $conditions);

        $table = $queryBuilder->getModel()->getTable();

        if (array_key_exists('approved', $conditions)) {
            $approved = null === $conditions['approved'] || '' === $conditions['approved'] ? null : $conditions['approved'];
            if (null !== $approved) {
                $queryBuilder->where("{$table}.approved",'=',$approved);
            }
        }
    }
}

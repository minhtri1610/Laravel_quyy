<?php
declare(strict_types=1);

namespace App\Models\Repositories\Eloquent;

use Throwable;
use Exception;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Entities\UserService;
use App\Models\Repositories\Contracts\UserServiceRepositoryInterface;
use App\Models\Repositories\Eloquent\Repository;

final class UserServiceRepository extends Repository implements UserServiceRepositoryInterface
{
    protected static $model = UserService::class;
}

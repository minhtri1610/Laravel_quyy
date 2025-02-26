<?php
declare(strict_types=1);
namespace App\Services\UserService;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model as Entity;
use App\Models\Entities\UserService;
use App\Models\Repositories\Contracts\UserServiceRepositoryInterface;
use App\Services\Traits\Conditionable;

class ListUserServicesService
{
    use Conditionable;

    private $repository;
    private $request;

    public function __construct(UserServiceRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function find($id): ?Entity
    {
        return $this->repository->find($id);
    }

    public function list($conditions = null, $limit = null, $offset = null): Collection
    {
        if (! is_array($conditions)) {
            $conditions = $this->conditionQueryToArray($conditions);
        }        

        return $this->repository->list($conditions, $limit, $offset);
    }
    public function sqlList($domain, $name, array $parameters = [], array $conditions = [], array $orderBy = [], array $paginate = []): Collection
    {
        return $this->repository->sqlList($domain, $name, $parameters, $conditions, $orderBy);
    }
    public function paginate($conditions = null, int $perPage = 10): LengthAwarePaginator
    {
        if (! is_array($conditions)) {
            $conditions = $this->conditionQueryToArray($conditions);
        }        

        return $this->repository->paginate($conditions, $perPage);
    }
}


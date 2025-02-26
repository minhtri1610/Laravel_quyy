<?php
declare(strict_types=1);
namespace App\Services\UserService;

use Throwable;
use Illuminate\Http\Request;
use DB;
use App\Models\Entities\UserService;
use App\Models\Repositories\Contracts\UserServiceRepositoryInterface;

class DeleteUserServiceService
{
    private $repository;
    private $request;

    public function __construct(UserServiceRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function delete(): UserService
    {
        try {
            return DB::transaction(function () {
                $user_service = $this->repository->find($this->request->offsetGet('id'));
                $this->repository->delete($user_service);

                return $user_service;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    public function restore(): UserService
    {
        try {
            return DB::transaction(function () {
                $user_service = $this->repository->findOnlyTrashed($this->request->offsetGet('id'));
                $this->repository->restore($user_service);

                return $user_service;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}

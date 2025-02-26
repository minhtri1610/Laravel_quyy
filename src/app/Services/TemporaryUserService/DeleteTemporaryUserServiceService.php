<?php
declare(strict_types=1);
namespace App\Services\TemporaryUserService;

use Throwable;
use Illuminate\Http\Request;
use DB;
use App\Models\Entities\TemporaryUser;
use App\Models\Repositories\Contracts\TemporaryUserServiceRepositoryInterface;

class DeleteTemporaryUserServiceService
{
    private $repository;
    private $request;

    public function __construct(TemporaryUserServiceRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
    }

    public function delete(): TemporaryUser
    {
        try {
            return DB::transaction(function () {
                $temporary_user_service = $this->repository->find($this->request->offsetGet('id'));
                $this->repository->delete($temporary_user_service);

                return $temporary_user_service;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    public function restore(): TemporaryUser
    {
        try {
            return DB::transaction(function () {
                $temporary_user_service = $this->repository->findOnlyTrashed($this->request->offsetGet('id'));
                $this->repository->restore($temporary_user_service);

                return $temporary_user_service;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}

<?php
declare(strict_types=1);
namespace App\Services\UserService;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Entities\UserService;
use App\Models\Repositories\Contracts\UserServiceRepositoryInterface;
use App\Http\Requests\UserService\SaveUserServiceRequestFilter;
use App\Http\Requests\UserService\SaveUserServiceRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class UpdateUserServiceService
{
    use Validatable;

    private $repository;
    private $request;

    public function __construct(UserServiceRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;
        $this->setFormRequest(new SaveUserServiceRequest());
        $this->init();
    }

    public function init()
    {
        return null;
    }

    public function update($inputs = [])
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        try {
            return DB::transaction(function () use ($inputs) {
                $qrCodeHtml = (string) $inputs['qr_code'];
                
                $user_service = $this->repository->find($inputs['id']);
                $user_service = $this->repository->edit($user_service, ['qr_code' => $qrCodeHtml]);
                $user_service = $this->repository->persist($user_service);
                return $user_service;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    public function updateByUid($inputs = [])
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        try {
            return DB::transaction(function () use ($inputs) {
                $user_service = $this->repository->findByUid($inputs['uid']);
                $user_service = $this->repository->edit($user_service, $inputs);
                $user_service = $this->repository->persist($user_service);
                return $user_service;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}

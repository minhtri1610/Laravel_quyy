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

class CreateUserServiceService
{
    use Filterable,
        Validatable;

    private $repository;
    private $request;

    public function __construct(UserServiceRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new SaveUserServiceRequestFilter());
        $this->setFormRequest(new SaveUserServiceRequest());
        $this->init();
    }

    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();
    }

    public function create($inputs = null): UserService
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        $inputs = convertSnakeCase($inputs);
        try {
            return DB::transaction(function () use ($inputs) {
                $user_service = $this->repository->new($inputs);
                $user_service = $this->repository->persist($user_service);
                return $user_service;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}

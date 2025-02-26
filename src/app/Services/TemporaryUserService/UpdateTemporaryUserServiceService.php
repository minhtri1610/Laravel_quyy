<?php
declare(strict_types=1);
namespace App\Services\TemporaryUserService;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Entities\TemporaryUser;
use App\Models\Repositories\Contracts\TemporaryUserServiceRepositoryInterface;
use App\Http\Requests\TemporaryUserService\SaveTemporaryUserServiceRequestFilter;
use App\Http\Requests\TemporaryUserService\SaveTemporaryUserServiceRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;

class UpdateTemporaryUserServiceService
{
    use Filterable,
        Validatable;

    private $repository;
    private $request;

    public function __construct(TemporaryUserServiceRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setRequestFilter(new SaveTemporaryUserServiceRequestFilter());
        $this->setFormRequest(new SaveTemporaryUserServiceRequest());
        $this->init();
    }

    public function init()
    {
        if (! $this->request->isMethod('GET')) {
            $this->filterInputs();
            return;
        }

        $this->request->flush();

        $temporary_user_service = $this->repository->find($this->request->offsetGet('id'));

        $defaults = $temporary_user_service->toArray();

        $this->request->merge($defaults);
    }

    public function update($inputs = null): TemporaryUser
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        $inputs = convertSnakeCase($inputs);
        try {
            return DB::transaction(function () use ($inputs) {
                $temporary_user_service = $this->repository->find($this->request->offsetGet('id'));
                $temporary_user_service = $this->repository->edit($temporary_user_service, $inputs);
                $temporary_user_service = $this->repository->persist($temporary_user_service);

                return $temporary_user_service;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}

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
use App\Services\Traits\Validatable;

class CreateTemporaryUserServiceService
{
    use Validatable;

    private $repository;
    private $request;

    public function __construct(TemporaryUserServiceRepositoryInterface $repository, SaveTemporaryUserServiceRequest $request)
    {
        $this->repository = $repository;
        $this->request    = $request;

        $this->setFormRequest(new SaveTemporaryUserServiceRequest());
        $this->init();
    }

    public function init()
    {
        $this->request->flush();
    }

    public function create($inputs = null): TemporaryUser
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except(['action', 'submit', '_token']);
        }
        try {
            return DB::transaction(function () use ($inputs) {
                $temporary_user_service = $this->repository->new($inputs);
                $temporary_user_service = $this->repository->persist($temporary_user_service);
                return $temporary_user_service;
            });
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}

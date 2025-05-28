<?php

declare(strict_types=1);

namespace App\Services\UserService;

use Throwable;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Models\Entities\User;
use App\Models\Repositories\Contracts\UserServiceRepositoryInterface;
use App\Http\Requests\UserService\SaveUserServiceRequestFilter;
use App\Http\Requests\UserService\SaveUserServiceRequest;
use App\Services\Traits\Filterable;
use App\Services\Traits\Validatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportUserServiceService
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
        $this->request->flush();
    }

    public function import($items)
    {
        $user_service = $this->repository->insert($items);
        return $user_service;
    }


}

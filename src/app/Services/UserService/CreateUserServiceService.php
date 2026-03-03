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

class CreateUserServiceService
{
    use Validatable;

    private $repository;
    private $request;

    public function __construct(UserServiceRepositoryInterface $repository, Request $request)
    {
        $this->repository = $repository;
        $this->request = $request;

        $this->setFormRequest(new SaveUserServiceRequest());
        $this->init();
    }

    public function init()
    {
        $this->request->flush();
    }

    public function create($data_temp, $inputs = null): User
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        $inputs = $this->sanitizeData($data_temp, $inputs);

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

    public function updateOrCreate($user_service, $inputs = null)
    {
        if (is_null($inputs)) {
            $inputs = $this->request->except('action');
        }
        $inputs = $this->sanitizeData($user_service, $inputs);
        try {
            $conditions = [
                'uid_code' => $inputs['uid_code']
            ];
            $item = $this->repository->updateOrCreate($conditions, $inputs);
            return $item;
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    private function sanitizeData($data_temp, $inputs): array
    {
        if (is_array($data_temp)) {
            $data_temp = (object) $data_temp;
        }
        $nickname = $data_temp->nickname ?? '';
        if (!empty($inputs)) {
            $nickname = $inputs['nickname'] ?? '';
        }

        return [
            'name' => trim($data_temp->full_name ?? 'No Name'),
            'gender' => $data_temp->gender ?? 'male',
            'address' => trim($data_temp->address ?? ''),
            'country' => trim($data_temp->province ?? ''),
            'city' => trim($data_temp->district ?? ''),
            'state' => trim($data_temp->ward ?? ''),
            'email' => strtolower(trim($data_temp->email ?? '')),
            'password' => $data_temp->password ?? Hash::make(Carbon::parse($data_temp->birth_date)->format('Y-m-d')),
            'phone' => preg_replace('/[^0-9]/', '', $data_temp->phone_number ?? ''),
            'birth_date' => $data_temp->birth_date ?? null,
            'nickname' => $nickname ?? '',
            'uid_code' => $data_temp->uid_code ?? ($inputs['uid_code'] ?? null),
            'is_active' => config('conts.is_active'),
            'date_registered' => !empty($data_temp->date_registered) ? Carbon::parse($data_temp->date_registered)->format('Y-m-d') : (isset($data_temp->created_at) ? Carbon::parse($data_temp->created_at)->format('Y-m-d') : Carbon::now()->format('Y-m-d')),
            'uid' => $data_temp->uid ?? ($inputs['uid'] ?? Str::uuid()->toString()),
            'qr_code' => $data_temp->qr_code ?? ($inputs['qr_code'] ?? null),
        ];
    }
}

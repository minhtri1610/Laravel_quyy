<?php
declare(strict_types=1);

namespace App\Http\Requests\UserService;

use App\Http\Requests\RequestFilter;

class SaveUserServiceRequestFilter extends RequestFilter
{
    public function filterInputs(array $inputs): array
    {
        $inputs = parent::filterInputs($inputs);

        $encoding = mb_internal_encoding();

        foreach ($inputs as $attribute => $value) {
            switch ($attribute) {
                default:
                    break;
            }
            $inputs[$attribute] = $value;
        }

        return $inputs;
    }
}

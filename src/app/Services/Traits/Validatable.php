<?php
declare(strict_types=1);

namespace App\Services\Traits;

use InvalidArgumentException;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;


trait Validatable
{
    /**
     * @var FormRequest  $formRequest
     */
    protected $formRequest;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     *
     * @param  FormRequest  $formRequest
     * @return void
     */
    public function setFormRequest(FormRequest $formRequest)
    {
        $this->formRequest = $formRequest;
    }

    /**
     *
     * @return Validator
     * @throws InvalidArgumentException
     */
    public function makeValidator(): Validator
    {
        if (! $this->request instanceof Request) {
            throw new InvalidArgumentException('This service must have $request parameter of the \Illuminate\Http\Request instance.');
        }
        $this->validator = ValidatorFacade::make(
            $this->request->except(['action', 'submit']),
            $this->formRequest->rules(),
            $this->formRequest->messages(),
            $this->formRequest->attributes()
        );
        return $this->validator;
    }

    /**
     *
     * @param  array|null  $inputs
     * @return bool
     */
    public function passesValidation($inputs = null): bool
    {
        if (! $this->validator instanceof Validator) {
            $this->makeValidator();
        }
        if (is_array($inputs)) {
            $this->validator->setData($inputs);
        }
        $isValidated = $this->validator->passes();
        if ($isValidated && is_json(request()->getContent())) {
            request()->merge(json_decode(request()->getContent(), true,512, JSON_BIGINT_AS_STRING));
        }        
        return $isValidated;
    }

    /**
     *
     * @param  array|null  $inputs
     * @return bool
     */
    public function failsValidation($inputs = null): bool
    {
        if (! $this->validator instanceof Validator) {
            $this->makeValidator();
        }
        if (is_array($inputs)) {
            $this->validator->setData($inputs);
        }
        return $this->validator->fails();
    }

    /**
     *
     * @param  array|null  $inputs
     * @return MessageBag
     */
    public function getValidationMessages($inputs = null): MessageBag
    {
        if (! $this->validator instanceof Validator) {
            $this->makeValidator();
        }
        if (is_array($inputs)) {
            $this->validator->setData($inputs);
        }
        return $this->validator->messages();
    }
}

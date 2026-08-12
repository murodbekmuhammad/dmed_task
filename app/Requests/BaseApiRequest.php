<?php

namespace App\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @class BaseApiRequest
 *
 * @package App\Requests
 */
abstract class BaseApiRequest extends FormRequest
{
    /**
     * @var $params
     */
    public $params;

    /**
     * getParams
     * Get parameters
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->params::fromRequest($this);
    }

    /**
     * failedValidation
     *
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator): mixed
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $errors
        ], 422));
    }
}

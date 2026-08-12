<?php

namespace App\Requests\Auth;

use App\Requests\BaseApiRequest;

/**
 * @class LoginRequest
 *
 * @package App\Requests\Auth
 */
class LoginRequest extends BaseApiRequest
{
    /**
     * authorize
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}

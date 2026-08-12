<?php

namespace App\Http\Controllers;

use App\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @class LoginController
 *
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    /**
     * login
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->errorJson('Unauthorized.', 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * respondWithToken
     * Format the token response.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->successJson([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ], 'Login successful');
    }
}


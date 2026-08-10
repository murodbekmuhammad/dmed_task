<?php

namespace App\Http\Controllers;

use App\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->errorJson('Unauthorized.', 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Format the token response.
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


<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

use function auth, response;

class AuthController extends Controller
{

    /**
     * Returns the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Logs the user out by invalidating the token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

}

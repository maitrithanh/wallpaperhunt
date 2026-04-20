<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;


class AuthController extends APIController
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);

        $token = $user->createToken("api-token")->plainTextToken;

        return $this->success(201, "User registered successfully!", [
            'user' => $user,
            'token' => $token,
        ]);

    }

    public function login(LoginRequest $request)
    {
        // Handle login logic here
    }
}

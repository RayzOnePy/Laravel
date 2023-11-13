<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;

class AuthController extends Controller
{
    public function signUp(Request $request): JsonResponse
    {
        $user = User::create([
            'email' => $request['email'],
            'password' => $request['password'],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
        ]);

        $token = $user->createToken('myapp-token')->plainTextToken;

        $user->forceFill([
            'remember_token' => $token,
        ])->save();
        return response()->json(['success' => true, 'code' => 201, 'message' => 'Success', 'token' => $token], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $user = User::query()->where('email', $request['email'])->where('password', $request['password'])->first();
        if ($user) {
            $token = $user->createToken('myapp-token')->plainTextToken;

            $user->forceFill(['remember_token' => $token])->save();

            return response()->json(['success' => true, 'code' => 200, 'message' => 'Success', 'token' => $token], 200);
        } else {
            return response()->json(['success' => false, 'code' => 401, 'message' => 'Authorization failed'], 401);
        }
    }

    public function logout(Request $request): ?JsonResponse
    {
        $request->user()->tokens()->delete();

        $request->user()->forceFill([
            'remember_token' => '',
        ])->save();

        return null;
    }
}

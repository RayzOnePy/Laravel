<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function signUp(RegistrationRequest $request): JsonResponse
    {
        $user = User::create($request->all());

        $token = $user->createToken('myapp-token')->plainTextToken;

        $user->forceFill([
            'remember_token' => $token,
        ])->save();
        return response()->json(['success' => true, 'code' => 201, 'message' => 'Success', 'token' => $token], 201);
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::getByEmailAndPassword($request->get('email'), $request->get('password'));
        if ($user) {
            $token = $user->createToken('myapp-token')->plainTextToken;

            $user->forceFill(['remember_token' => $token])->save();

            return response()->json(['success' => true, 'code' => 200, 'message' => 'Success', 'token' => $token], 200);
        }

        return response()->json(['success' => false, 'code' => 401, 'message' => 'Authorization failed'], 401);
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

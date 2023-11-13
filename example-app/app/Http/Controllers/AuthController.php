<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Psy\Util\Json;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function signUp(Request $request): JsonResponse
    {
        $rules = array(
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(3)->mixedCase()->numbers()],
            'first_name' => ['required', 'string', 'min:2'],
            'last_name' => ['required', 'string'],
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(['message' => 'Validation error'], 422);
        }

        $validated = $validator->validated();

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

    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $rules = array(
            'email' => ['required'],
            'password' => ['required'],
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json(['message' => 'Validation error'], 422);
        }

        $validated = $validator->validated();

        $user = User::getByEmailAndPassword($validated['email'], $validated['password']);
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

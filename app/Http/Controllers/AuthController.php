<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    /**
     * Register
     * 
     * @param RegisterRequest $registerRequest
     * @return JsonResponse
     */
    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        DB::beginTransaction();

        try {

            $user = User::create([
                'name'     => $registerRequest->name,
                'email'    => $registerRequest->email,
                'password' => Hash::make($registerRequest->password),
            ]);

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return response()->json([
            'status'  => "OK",
            'success' => true,
            'message' => 'User added successfully',
            'data'    => $user
        ], 201);
    }

    /**
     * Login
     * 
     * @param LoginRequest $loginRequest
     * @return JsonResponse
     */
    public function login(LoginRequest $loginRequest): JsonResponse
    {
        DB::beginTransaction();

        try {

            $user = User::where('email', $loginRequest->email)->first();

            if (! $user || ! Hash::check($loginRequest->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
        return response()->json([
            'status'      => "OK",
            'success'      => true,
            'message'      => 'User successfully logged in',
            'token_type'   => 'Bearer',
            'access_token' => $token
        ], 201);
    }

    /**
     * logout
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status'      => "OK",
            'message'     => 'Logged out successfully'
        ]);
    }

    /**
     * change password
     * 
     * @param ChangePasswordRequest $changePasswordRequest
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $changePasswordRequest): JsonResponse
    {
        $user = $changePasswordRequest->user();

        if (!Hash::check($changePasswordRequest->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->password = Hash::make($changePasswordRequest->new_password);
        $user->save();

        DB::commit();

        return response()->json([
            'status'  => "OK",
            'success' => true,
            'message' => 'Password changed successfully'
        ], 200);
    }
}

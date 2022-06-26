<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Models\Wine;
use Faker\Generator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $user = User::query()->whereEmail($request->email)->firstOrFail();

        $token = $user->createToken($request->email);
        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::query()->create($validated);

        $token = $user->createToken($validated['email']);

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    /**
     * @param Generator $generator
     * @return JsonResponse
     */
    public function mockUser(Generator $generator): JsonResponse
    {
        return response()->json([
            'name'=> $generator->name,
            'email' => $generator->email,
            'password' => 'user',
        ]);
    }

    /**
     * @param Generator $generator
     * @return JsonResponse
     */
    public function mockWine(Generator $generator): JsonResponse
    {
        return response()->json(Wine::factory()->makeOne());
    }
}

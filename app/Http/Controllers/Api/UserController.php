<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreUserWineRequest;
use App\Http\Requests\StoreWineRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\WineResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index(): UserCollection
    {
        $users = Cache::rememberForever('users', function ()  {
            return User::query()->with('favoriteWine')->latest()->simplePaginate();
        });
        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $user = $request->validated();
        $user['password'] = Hash::make($user['password']);
        if ($request->hasFile('avatar')) {
            $user['avatar'] = $request->file('avatar')->store('avatars');
        }
        $user = User::query()->create($user);
        Cache::forget('users');	
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource|Response
     */
    public function update(UpdateUserRequest $request, User $user): Response|UserResource
    {
        $validated = $request->validated();

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars');
        }


        $user->update($validated);
        Cache::forget('users');	
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        if ($user->avatar) {
            Storage::delete($user->avatar);
        }
        $user->delete();
        return \response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Create user's wine
     * @param User $user
     * @return UserResource
     */
    public function usersWine(User $user): UserResource
    {
        return new UserResource($user->load('wines'));
    }

    /**
     * Store user's wine
     * @param User $user
     * @param StoreUserWineRequest $request
     * @return WineResource
     */
    public function usersWineStore(User $user, StoreUserWineRequest $request): WineResource
    {
        $validated = $request->validated();
        if ($request->hasFile('images')) {
            if (is_iterable($request->file('images'))) {
                $validated['images'] = $request->file('images')->map(function ($file) {
                    return $file->store('wines');
                })->toJson();
            } else {
                $validated['images'] = $request->file('images')->store('wines');
            }

        }
        $wine = $user->wines()->create($validated);
        return new WineResource($wine);
    }

}

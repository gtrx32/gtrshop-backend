<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('cart.cartItems');

        return response()->json(new UserResource($user));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (!empty($data['password'])) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is invalid'], 422);
            }
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json(new UserResource($user));
    }
}

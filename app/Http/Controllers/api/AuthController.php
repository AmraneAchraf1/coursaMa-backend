<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string',
            'avatar' => 'nullable|image',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/avatars', $fileName);
            $request->avatar = $fileName;
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => [
                'name' => $user->name,
                'phone' => $user->phone,
                'avatar' => $user->avatar ? Storage::disk('public')->url('avatars/' . $user->avatar) : null,
            ]
        ], 201);

    }

    /**
     * Login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = request(['phone', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid phone or password',
            ], 401);
        }

        $user = $request->user();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'phone' => $user->phone,
                'avatar' => $user->avatar ? Storage::disk('public')->url('avatars/' . $user->avatar) : null,
            ]
        ], 200);
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successfully',
        ], 200);
    }

    /**
     * Get authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
       $user = $request->user();

        return response()->json([
            'name' => $user->name,
            'phone' => $user->phone,
            'avatar' => $user->avatar ? Storage::disk('public')->url('avatars/' . $user->avatar) : null,

        ], 200);
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'avatar' => 'nullable|string',
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $new_avatar = $file->storeAs('public/avatars', $fileName);
            $old_avatar = $user->avatar;
            if ($old_avatar && $new_avatar) {
                Storage::delete('public/avatars/' . $old_avatar);
            }
        }

        $fileName = $fileName ?? $user->avatar;

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'avatar' => $fileName,
        ]);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;

use App\Helpers\ApiResponse;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Mail::to($user->email)->send(new WelcomeMail($user));
        //$user->sendEmailVerificationNotification();

        return ApiResponse::success(new UserResource($user), 'User registered successfully');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return ApiResponse::error('Invalid credentials', [], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        if (! $request->hasValidSignature()) {
            return ApiResponse::error('Invalid or expired verification link.', [], 403);
        }

        $user = User::find($id);

        if (! $user) {
            return ApiResponse::error('User not found.', [], 404);
        }

        if (! hash_equals(
            sha1($user->getEmailForVerification()),
            $hash
        )) {
            return ApiResponse::error('Invalid verification link.', [], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return ApiResponse::success([], 'Email is already verified.');
        }

        $user->markEmailAsVerified();

        return ApiResponse::success([], 'Email verified successfully.');
    }


}

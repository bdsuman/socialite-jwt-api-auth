<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Your registration logic here
    }

    public function login(Request $request)
    {
        // Your login logic here

        $token = JWTAuth::fromUser(Auth::user());

        return response()->json(['token' => $token], 200);
    }

    public function redirectToGoogle()
    {
       
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        $existingUser = User::where('email', $user->email)->first();
        
        if ($existingUser) {
            $token = JWTAuth::fromUser($existingUser);
            return response()->json(['token' => $token], 200);
        } else {
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => bcrypt(Str::random(16)),
            ]);
            
            $token = JWTAuth::fromUser($newUser);
            return response()->json(['token' => $token], 200);
        }
    }
}

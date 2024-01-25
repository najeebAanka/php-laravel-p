<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use App\Models\Cart;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{

    public function loginWithGoogle(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->token);
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'name_ar' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt("12345678"),
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'provider_access_token' => $request->token,
                'status' => 'active', // Set default status to 'active'
            ]);
        } else {
            $user->provider = 'google';
            $user->provider_id = $googleUser->getId();
            $user->provider_access_token = $request->token;
            $user->save();
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }


    public function loginWithApple(Request $request)
    {
        try {
            $appleUser = Socialite::driver('apple')->stateless()->userFromToken($request->token);

            $user = User::where('email', $appleUser->getEmail())->first();

            if (!$user) {
                // If the user doesn't exist, create a new user
                $user = User::create([
                    'name' => $appleUser->getName(),
                    'email' => $appleUser->getEmail(),
                    'provider' => 'apple',
                    'provider_id' => $appleUser->getId(),
                    'provider_access_token' => $request->token,
                ]);
            } else {
                // Update the user's provider information
                $user->provider = 'apple';
                $user->provider_id = $appleUser->getId();
                $user->provider_access_token = $request->token;
                $user->save();
            }

            // Generate a new API token for the user
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to login with Apple',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function loginWithFacebook(Request $request)
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->userFromToken($request->token);
            $user = User::where('email', $facebookUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'provider' => 'facebook',
                    'provider_id' => $facebookUser->getId(),
                    'provider_access_token' => $request->token,
                ]);
            } else {
                $user->provider = 'facebook';
                $user->provider_id = $facebookUser->getId();
                $user->provider_access_token = $request->token;
                $user->save();
            }

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while logging in with Facebook.'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


use App\Mail\ResetPasswordMail;
use App\Models\Cart;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     try {
    //         // Validate request input
    //         $validator = Validator::make($request->all(), [
    //             'email' => 'required|email',
    //             'password' => 'required',
    //         ]);

    //         if ($validator->fails()) {
    //             $error = $this->failedValidation($validator);
    //             return $this->formResponse($error, null, 400);
    //         }

    //         // Attempt to authenticate user
    //         if (Auth::attempt($request->only('email', 'password'))) {
    //             $user = Auth::user();
    //             $cart = Cart::where('user_id', $user->id)->first();
    //             $cart_id = $cart ? $cart->id : null;
    //             $token = $user->createToken('auth-token')->plainTextToken;
    //             return $this->formResponse('login successful', ['token' => $token, 'user' => $user, 'cart_id' => $cart_id], 200);
    //         }

    //         // Authentication failed
    //         return $this->formResponse('Invalid email or password', null, 401);
    //     } catch (\Exception $exception) {
    //         return $this->formResponse("There is some error while trying to login", null, 500);
    //     }
    // }

    public function login(Request $request)
    {
        try {
            // Validate request input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                $error = $this->failedValidation($validator);
                return $this->formResponse($error, null, 400);
            }

            // Attempt to authenticate user
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $cart = Cart::where('user_id', $user->id)->first();

                if ($cart && $cart->status == 'checked_out') {
                    // Create a new cart
                    $newCart = new Cart();
                    $newCart->user_id = $user->id;
                    $newCart->status = 'active';
                    $newCart->save();

                    $cart_id = $newCart->id;
                } else {
                    $cart_id = $cart ? $cart->id : null;
                }

                $token = $user->createToken('auth-token')->plainTextToken;
                return $this->formResponse('login successful', ['token' => $token, 'user' => $user, 'cart_id' => $cart_id], 200);
            }

            // Authentication failed
            return $this->formResponse('Invalid email or password', null, 401);
        } catch (\Exception $exception) {
            return $this->formResponse(
                "There is some error while trying to login",
                null,
                500
            );
        }
    }


    public function signUp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'phone' => 'required|regex:/^[+]?[0-9]+$/|unique:users,phone'
            ]);

            if ($validator->fails()) {
                $error = $this->failedValidation($validator);
                return $this->formResponse($error, null, 400);
            }

            $existingUser = User::where('email', $request->email)
                ->orWhere('phone', $request->phone)
                ->first();

            if ($existingUser) {
                return $this->formResponse('A user with this email or phone number already exists', null, 409);
            }

            $guest = Cart::where('user_ip', $request->device_id)->first();
            if ($guest) {
                // return "herer";
                $user = User::find($guest->user_id);
                if ($user) {
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->password = Hash::make($request->password);
                    $user->phone = $request->phone;
                    $user->name_ar = $request->name_ar;
                    $user->user_type = $request->user_type ?? 'customer';
                    $user->save();
                    $guest->user_ip = -1;
                    $guest->save();
                    $cartId = $guest->id;
                } else {
                    return $this->formResponse("User not found", null, 404);
                }
            } else {

                $user = new User([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'name_ar' => $request->name_ar,
                    'user_type' => $request->user_type ?? 'customer'
                ]);
                $user->save();

                $cart = new Cart([
                    'user_id' => $user->id,
                    'user_ip' => -1,
                ]);
                $cart->save();
                $cartId = $cart->id;
            }

            $token = $user->createToken('auth-token')->plainTextToken;

            return $this->formResponse('User created successfully', [
                'token' => $token,
                'user' => $user,
                'cart_id' => $cartId,
            ], 200);
        } catch (\Exception $exception) {
            return $this->formResponse("There is some error while trying to signup", null, 500);
        }
    }


    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->formResponse('Validation failed', $this->failedValidation($validator), 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->formResponse('User not found', 'No user found with that email address.', 404);
            }

            $token = rand(1000, 9999);

            $existingToken = DB::table('password_reset_tokens')->where('email', $user->email)->first();

            if ($existingToken) {
                DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            }

            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);

            // send the OTP via email or SMS
            // if ($user->phone_number) {
            //     // send via SMS
            //     $twilio = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
            //     $twilio->messages->create(
            //         $user->phone_number,
            //         [
            //             'from' => env('TWILIO_PHONE_NUMBER'),
            //             'body' => "Your OTP is $token. Use this to reset your password.",
            //         ]
            //     );
            // } else {

            // send via email
            Mail::to($user->email)->send(new ResetPasswordMail($token));
            // }

            return $this->formResponse('Success', 'We have sent an OTP to your email or phone number!', 200);
        } catch (\Exception $exception) {
            return $this->formResponse("error sending Otp code", null, 400);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6|confirmed',
                'otp' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->formResponse('Validation failed', $this->failedValidation($validator), 400);
            }

            $passwordReset = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->otp)
                ->first();

            if (!$passwordReset) {
                return $this->formResponse('Invalid OTP', 'Invalid OTP.', 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->formResponse('User not found', 'No user found with that email address.', 404);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return $this->formResponse('Success', 'Your password has been reset!', 200);
        } catch (\Exception $exception) {
            return $this->formResponse("error reseting you password", null, 400);
        }
    }



    public function updatePassword(Request $request)
    {
        try {
            $validator =
                Validator::make(
                    $request->all(),
                    [
                        'current_password' => 'required',
                        'password' => 'required|string|min:8',
                    ]
                );

            $user = Auth::user();

            if ($validator->fails()) {
                return $this->formResponse('Validation failed', $this->failedValidation($validator), 400);
            }

            if (Hash::check($request->current_password, $user->password)) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);

                return $this->formResponse('Your password has been updated!', null, 200);
            }
        } catch (\Exception $exception) {
            return $this->formResponse("error reseting you password", null, 400);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\sendOtpMail;
use Exception;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class OtpController extends Controller
{
    public function sendMobileOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^[0-9]{10}$/',
        ]);

        if ($validator->fails()) {
            $error = $this->failedValidation($validator);
            return $this->formResponse($error, null, 400);
        }

        $user = Auth::user();
        if ($user->isPhoneVerified != 1) {
            $otp = mt_rand(1000, 9999);
            $user = User::where('id', auth()->user()->id)->update(['otp' => $otp]);

            $receiverNumber = $request->phone;

            $message = "Your login code is " . $otp;
            try {
                $accountSid = getenv("TWILIO_SID", "ACd57fa249444bff46e413f1d98d0116d4");
                $authToken = getenv("TWILIO_TOKEN", "73f2d5a59613fc073318bcfe19391bed");
                $twilioNumber = getenv("TWILIO_FROM", "+15856327188");

                $client = new Client("ACd57fa249444bff46e413f1d98d0116d4", "73f2d5a59613fc073318bcfe19391bed");

                $resp = $client->messages->create("+1" . $receiverNumber, [
                    'from' => "+15627844695",
                    'body' => $message
                ]);

                return $this->formResponse('OTP sent successfully', null, 200);
            } catch (Exception $e) {
                info("Error: " . $e->getMessage());
                return $this->formResponse('Failed to send OTP', $e->getMessage(), 500);
            }
        } else {
            return $this->formResponse('You are already authenticated', null, 400);
        }
    }


    public function verifyMobileOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
            ]);

            if ($validator->fails()) {
                $errorMessage = $this->failedValidation($validator);
                return $this->formResponse($errorMessage, null, 400);
            }

            $user = User::where('id', auth()->user()->id)
                ->where('otp', $request->otp)
                ->first();

            if (!is_null($user)) {
                $request->user()->otp = null;
                $request->user()->save();
                $userData = User::where('id', auth()->user()->id)->update(['isPhoneVerified' => 1]);
                $userD = User::where('id', auth()->user()->id)->first();
                $token = $userD->createToken('apiToken')->plainTextToken;
                return $this->formResponse('OTP verification successful', ['user' => $userD, 'token' => $token], 200);
            } else {
                return $this->formResponse('Invalid OTP', null, 400);
            }
        } catch (\Throwable $th) {
            return $this->formResponse('Failed to verify OTP', $th->getMessage(), 500);
        }
    }



    public function requestOtp(Request $request)
    {
        // try {
        $email = $request->input('email');

        $otp = rand(1000, 9999);
        Log::info("OTP = " . $otp);
        $user = User::where('email', $email)->first();
        $user->otp = $otp;
        $user->save();

        $mail_details = [
            'subject' => 'Welcome to the dob_test App',
            'body' => 'Your dob_test OTP is: ' . $otp
        ];

        \Mail::to($email)->send(new sendOtpMail($mail_details));

        return $this->formResponse("OTP sent successfully", null, 200);
        // } catch (\Throwable $th) {
        //     return $this->formResponse("An error occurred while processing your request", null, 500);
        // }
    }


    public function verifyOtp(Request $request)
    {
        try {
            $email = $request->input('email');

            $request->validate([
                'otp' => 'required'
            ]);
            $user = User::where('email', $email)->first();
            if ($user->otp == $request->otp) {
                $user->otp = null;
                $user->isEmailVerified = true;
                $user->save();

                // $token = $user->createToken('apiToken')->plainTextToken;

                return $this->formResponse("OTP authentication successful", $user, 200);
            } else {
                return $this->formResponse("Invalid OTP provided", null, 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->failedValidation($e->validator);
        } catch (\Throwable $th) {
            return $this->formResponse("An error occurred while processing your request", null, 500);
        }
    }
}

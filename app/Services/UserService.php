<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\User;

class UserService
{
    /**
     * Retrieve the user's account information.
     *
     * @return array
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Get all the errors thrown
        $errors = collect($validator->errors());
        // Manipulate however you want. I'm just getting the first one here,
        // but you can use whatever logic fits your needs.
        $error = $errors->unique()->first();
        // Either throw the exception, or return it any other way.
        return $error[0];
    }

    public function formResponse($message, $data, $status)
    {
        if ($status == 200 || $status == 206)
            return response()->json(
                ['message' => $message, 'data' => $data, 'error' => null],
                $status,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        else
            return response()->json(
                ['message' => $message, 'data' => null, 'error' => $data],
                $status,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
    }
    public function getAccountInfo()
    {
        try {
            $user = Auth::user();
            return $this->formResponse('Account information retrieved successfully.', $user, 200);
        } catch (\Exception $e) {
            return $this->formResponse('Error retrieving account information.', null, 500);
        }
    }

    /**
     * Update the user's account information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function updateAccountInfo(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . Auth::id(),
                'phone' => 'required|string',
            ]);

            $user = Auth::user();

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->phone = $validatedData['phone'];

            $user->save();

            return $this->formResponse("Account information updated successfully.", null, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->getMessageBag()->toArray();
            return $this->formResponse("Validation error", $errors, 422);
        } catch (\Exception $e) {
            return $this->formResponse("Error updating account information.", null, 400);
        }
    }


    /**
     * Delete the user's account (soft delete).
     *
     * @return array
     */
    public function deleteAccount(): array
    {
        try {
            $user = Auth::user();

            $user->delete();

            return [
                'data' => null,
                'message' => 'Account deleted successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'data' => null,
                'message' => 'Error deleting account.',
            ];
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Retrieve the user's account information.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccountInfo()
    {
        $data = $this->userService->getAccountInfo();

        return response()->json($data);
    }

    /**
     * Update the user's account information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAccountInfo(Request $request)
    {
        $data = $this->userService->updateAccountInfo($request);

        return response()->json($data);
    }

    /**
     * Delete the user's account (soft delete).
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAccount()
    {
        $data = $this->userService->deleteAccount();

        return response()->json($data);
    }
}

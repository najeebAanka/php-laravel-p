<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Validator;



class AdminController extends Controller
{



    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function logout(Request $request)
    {
        Session::flush();

        Auth::logout();

        return redirect('dashboard/login');
    }

    function login(Request $request)
    {

        $request->validate([

            'email' => 'required',
            'password' => 'required|min:1',

        ]);


        $user = \App\Models\User::where('user_type', '=', 'admin')->first();

        // $hashedPassword = Hash::make($user->password); 
        //     if ($user->email == $request->email && Hash::check($request->password, $hashedPassword)) {
        if ($user->email == $request->email && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            return   redirect('dashboard/products');
        } else {
            return back()->with('error', 'Username and password are not correct.');
        }
    }



    //----------------Store login


    function storeLogin(Request $request)
    {

        $request->validate([

            'email' => 'required',
            'password' => 'required|min:1',

        ]);

        $user = \App\Models\User::where('email', $request->email)->where('user_type', 'store')->get();

        // $hashedPassword = Hash::make($user->password); 
        //     if ($user->email == $request->email && Hash::check($request->password, $hashedPassword)) {
        if ($user->count() > 0) {
            foreach ($user as $u) {

                if (Hash::check($request->password, $u->password)) {

                    Auth::login($u);
                    return   redirect('dashboard/store-services' . '/' . $u->id);
                    
                } else {
                    return back()->with('error', 'Username and password are not correct.');
                }
            }
        } else {
            return back()->with('error', 'Username and password are not correct.');
        }
    }
}

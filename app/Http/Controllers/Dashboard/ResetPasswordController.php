<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Validator;

class ResetPasswordController extends Controller
{


public function resetPassword(Request $request)
    {


        $request->validate([
            'current' => 'required',
            'new' => 'required',
            'password' => 'required',
        ]);

        
        $data = \App\Models\User::where('user_type', '=', 'admin')->first();

        $one = $request->new;
        $two = Hash::make($request->password);

        if(Hash::check( $request->current, $data->password )){
            // if(1){
            if( Hash::check( $one, $two ) ){
                $data->password = Hash::make($request->password);
                $data->save();
                 return back()->with('msg'  ,'Edited successfully');
            }else{
                return back()->with('error'  ,'Passwords mismatch');
            }

            
        }else{
            return back()->with('error'  ,'Wrong password');
        }

    }


    public function storeResetPassword(Request $request, $id)
    {


        $request->validate([
            'current' => 'required',
            'new' => 'required',
            'password' => 'required',
        ]);

        
        $data = \App\Models\User::where('id', $id)->first();

        $one = $request->new;
        $two = Hash::make($request->password);

        if(Hash::check( $request->current, $data->password )){
            // if(1){
            if( Hash::check( $one, $two ) ){
                $data->password = Hash::make($request->password);
                $data->save();
                 return back()->with('msg'  ,'Edited successfully');
            }else{
                return back()->with('error'  ,'Passwords mismatch');
            }

            
        }else{
            return back()->with('error'  ,'Wrong password');
        }

    }




}
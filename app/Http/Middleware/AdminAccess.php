<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $userType)
    {
        if(auth()->user()->user_type == $userType){
            return $next($request);
        }

        // return response()->json(['You do not have permission to access this page.']); 
        return redirect('/dashboard/store-services'.'/'.auth()->user()->id);
    }

    // public function handle($request, Closure $next)
    // {
    //     if(auth()->user()->id == $request->route('id')){
    //         return $next($request);
    //     }

    //     return response()->json(['You do not have permission to access for this page.']);
    //     return redirect('/dashboard/store-services'.'/'.auth()->user()->id);
    // }
}

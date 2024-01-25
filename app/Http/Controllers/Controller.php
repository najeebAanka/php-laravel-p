<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function getLang(Request $request)
    {
        if ($request->header('Accept-Language')) {
            $lang = $request->header('Accept-Language');
            if ($lang == 'ar' || $lang == 'en') return $lang;
        }
        return 'en';
    }



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

    public function distance($lat1, $lon1, $lat2, $lon2, $unit = 'km')
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "KM") {
            $distance = ($miles * 1.609344);
        } else if ($unit == "MI") {
            $distance = $miles;
        } else {
            $distance = $miles;
        }

        return round($distance, 1);
    }
}

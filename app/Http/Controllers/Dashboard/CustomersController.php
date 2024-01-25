<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Exception;

class CustomersController extends Controller
{

	public function fetchAll()
	{
		$data = User::orderBy('id', 'desc')->where('user_type', 'customer')->paginate(25);

		foreach ($data as $c) {
			$c->image =  $c->buildImage();
		}

		return response()->json($data, 200);
	}


}

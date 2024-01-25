<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppSettingsController extends Controller
{

	public function fetchAll()
	{
		$data = AppSetting::orderBy('id', 'desc')->paginate(25);
		return response()->json($data, 200);
	}



	public function update(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'value_en' => 'required',
			'value_ar' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}


		$d = AppSetting::find($request->id);
		if ($d) {
			$dData = [
				'value_en' => $request->value_en,
				'value_ar' => $request->value_ar,
			];

			$d->update($dData);
			return response()->json([
				'status' => 200,
			]);
		} else {
			return response()->json([
				'message' => $request->id . ' Not found',
				'status' => 401,
			]);
		}
	}
}

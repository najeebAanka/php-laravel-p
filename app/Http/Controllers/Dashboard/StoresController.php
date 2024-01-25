<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Exception;

class StoresController extends Controller
{

	public function fetchAll()
	{
		$data = User::orderBy('id', 'desc')->where('user_type', 'store')->paginate(25);

		foreach ($data as $c) {
			$c->image =  $c->buildImage();

			$dat = Address::where('user_id', $c->id)->first();

			$c->country_name = $dat->country;
			$c->city_name = $dat->city;
		}

		return response()->json($data, 200);
	}


	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'name_ar' => 'required',
			'email' => 'required|unique:users,email',
			'phone' => 'required',
			'password' => 'required',
			'status' => 'required',
			'latitude' => 'required',
			'longitude' => 'required',
			'country_name' => 'required',
			'city_name' => 'required',
		]);
		
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		//--------------------------------validation to retreive error in toast
		// if ($validator->fails()) {
		// 	return response()->json(['errors' => $validator->errors()], 400);
		// }
		//---------------------------------------------------------------------

		$file = $request->only('image')['image'];
		$fileArray = array('image' => $file);
		$rules = array(
			'image' => 'mimes:jpg,png,jpeg,webp|required|max:500000' // max 10000kb
		);
		$validator = Validator::make($fileArray, $rules);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		} else {
			$uniqueFileName = uniqid() . '.' . $file->getClientOriginalExtension();
			$fileName = date('Y') . "/" . date("m") . "/" . date("d") . "/" . $uniqueFileName;
			try {
				if (!Storage::disk('public')->has('users/' . date('Y') . "/" . date("m") . "/" . date("d") . "/")) {
					Storage::disk('public')->makeDirectory('users/' . date('Y') . "/" . date("m") . "/" . date("d") . "/");
				}
				Image::make($file)->resize(512, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save(storage_path('app/public/users/' . $fileName));


				$dData = [
					'name' => $request->name,
					'name_ar' => $request->name_ar,
					'email' => $request->email,
					'phone' => $request->phone,
					'password' => bcrypt($request->password),
					'user_type' => 'store',
					'status' => $request->status,
					'latitude' => $request->latitude,
					'longitude' => $request->longitude,
					'image' => $fileName,
				];

				User::create($dData);

				$user = User::where('email', $request->email)->first();

				$addressData = [
					'user_id' => $user->id,
					'country' => $request->country_name,
					'city' => $request->city_name,
					'street' => 'street',
					'building' => 'building',
					'floor' => 'floor',
					'flat' => 'flat',
				];

				Address::create($addressData);

				return response()->json([
					'status' => 200,
				]);
			} catch (Exception $r) {
				return response()->json([
					'status' => 500,
					'message' => $r,
				]);
			}
		}
	}



	public function update(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'name_ar' => 'required',
			'email' => 'required',
			'phone' => 'required',
			'password' => 'required',
			'status' => 'required',
			'latitude' => 'required',
			'longitude' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		if ($request->hasFile('image')) {

			$file = $request->only('image')['image'];
			$fileArray = array('image' => $file);
			$rules = array(
				'image' => 'mimes:jpg,png,jpeg,webp|required|max:500000' // max 10000kb
			);
			$validator = Validator::make($fileArray, $rules);
			if ($validator->fails()) {
				return response()->json($this->failedValidation($validator), 400);
			} else {
				$uniqueFileName = uniqid() . '.' . $file->getClientOriginalExtension();
				$fileName = date('Y') . "/" . date("m") . "/" . date("d") . "/" . $uniqueFileName;
				try {
					if (!Storage::disk('public')->has('users/' . date('Y') . "/" . date("m") . "/" . date("d") . "/")) {
						Storage::disk('public')->makeDirectory('users/' . date('Y') . "/" . date("m") . "/" . date("d") . "/");
					}
					Image::make($file)->resize(512, null, function ($constraint) {
						$constraint->aspectRatio();
					})->save(storage_path('app/public/users/' . $fileName));


					$d = User::find($request->id);
					if ($d) {
						$dData = [
							'name' => $request->name,
							'name_ar' => $request->name_ar,
							'email' => $request->email,
							'phone' => $request->phone,
							'password' => bcrypt($request->password),
							'status' => $request->status,
							'latitude' => $request->latitude,
							'longitude' => $request->longitude,
							'image' => $fileName,
			
						];

						$d->update($dData);

						$address = Address::where('user_id', $request->id)->first();

						if ($address) {
							$addressData = [
								'user_id' => $request->id,
								'country' => $request->country_name,
								'city' => $request->city_name,
								'street' => 'street',
								'building' => 'building',
								'floor' => 'floor',
								'flat' => 'flat',
							];

							$address->update($addressData);

						} else {
							return response()->json([
								'message' => 'User address not found',
								'status' => 401,
							]);
						}
	
							
						return response()->json([
							'status' => 200,
						]);

					} else {
						return response()->json([
							'message' => $request->id . ' Not found',
							'status' => 401,
						]);
					}
				} catch (Exception $r) {
					return response()->json([
						'status' => 500,
						'message' => $r,
					]);
				}
			}
		} else {

			$d = User::find($request->id);
			if ($d) {
				$dData = [
					'name' => $request->name,
					'name_ar' => $request->name_ar,
					'email' => $request->email,
					'phone' => $request->phone,
					'password' => bcrypt($request->password),
					'status' => $request->status,
					'latitude' => $request->latitude,
					'longitude' => $request->longitude,
					];

				$d->update($dData);

				$address = Address::where('user_id', $request->id)->first();

						if ($address) {
							$addressData = [
								'user_id' => $request->id,
								'country' => $request->country_name,
								'city' => $request->city_name,
								'street' => 'street',
								'building' => 'building',
								'floor' => 'floor',
								'flat' => 'flat',
							];

							$address->update($addressData);

						} else {
							return response()->json([
								'message' => 'User address not found',
								'status' => 401,
							]);
						}

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


	public function delete(Request $request)
	{
		$data = User::find($request->id);

        $data->delete();
	}
}

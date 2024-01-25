<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Product;
use App\Models\Service;
use App\Models\StoresService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Exception;


class StoreProfileController extends Controller
{
	public function fetchAll($id)
	{
		$data = User::where('id', $id)->first();
		$data->image = $data->buildImage();

		$dat = Address::where('user_id', $data->id)->first();
		$data->country_name = $dat->country;
		$data->city_name = $dat->city;

		return response()->json($data, 200);
	}



	public function update(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'name_ar' => 'required',
			'email' => 'required',
			'phone' => 'required',
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
}

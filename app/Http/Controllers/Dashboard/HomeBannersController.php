<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Exception;

class HomeBannersController extends Controller
{

	public function fetchAll()
	{
		$data = HomeBanner::orderBy('id', 'desc')->paginate(25);

		foreach ($data as $c) {
			$c->image =  $c->buildImage();
			$c->exp_date = $c->expiration_date->format('Y-m-d');	
		}

		return response()->json($data, 200);
	}


	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'direction_type' => 'required',
			'direction_id' => 'required',
			'expiration_date' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

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
				if (!Storage::disk('public')->has('banners/' . date('Y') . "/" . date("m") . "/" . date("d") . "/")) {
					Storage::disk('public')->makeDirectory('banners/' . date('Y') . "/" . date("m") . "/" . date("d") . "/");
				}
				Image::make($file)->resize(512, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save(storage_path('app/public/banners/' . $fileName));


				$dData = [
					'direction_type' => $request->direction_type,
					'direction_id' => $request->direction_id,
					'expiration_date' => $request->expiration_date,
					'image' => $fileName,
				];

				HomeBanner::create($dData);
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
			'direction_type' => 'required',
			'direction_id' => 'required',
			'expiration_date' => 'required',
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
					if (!Storage::disk('public')->has('banners/' . date('Y') . "/" . date("m") . "/" . date("d") . "/")) {
						Storage::disk('public')->makeDirectory('banners/' . date('Y') . "/" . date("m") . "/" . date("d") . "/");
					}
					Image::make($file)->resize(512, null, function ($constraint) {
						$constraint->aspectRatio();
					})->save(storage_path('app/public/banners/' . $fileName));


					$d = HomeBanner::find($request->id);
					if ($d) {
						$dData = [
							'direction_type' => $request->direction_type,
				        	'direction_id' => $request->direction_id,
			                'expiration_date' => $request->expiration_date,
			                'image' => $fileName,
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
				} catch (Exception $r) {
					return response()->json([
						'status' => 500,
						'message' => $r,
					]);
				}
			}
		} else {

			$d = HomeBanner::find($request->id);
			if ($d) {
				$dData = [
					'direction_type' => $request->direction_type,
				    'direction_id' => $request->direction_id,
			        'expiration_date' => $request->expiration_date,
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



	public function delete(Request $request)
	{
		$data = HomeBanner::find($request->id);

        $data->delete();
	}


}

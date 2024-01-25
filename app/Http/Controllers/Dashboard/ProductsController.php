<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductsController extends Controller
{

	public function fetchAll()
	{
		$data = Product::orderBy('id', 'desc')->paginate(25);

		foreach ($data as $c) {
			$c->image =  $c->buildImage();
		}

		return response()->json($data, 200);
	}



	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name_en' => 'required',
			'name_ar' => 'required',

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
				if (!Storage::disk('public')->has('products/' . date('Y') . "/" . date("m") . "/" . date("d") . "/")) {
					Storage::disk('public')->makeDirectory('products/' . date('Y') . "/" . date("m") . "/" . date("d") . "/");
				}
				Image::make($file)->resize(512, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save(storage_path('app/public/products/' . $fileName));


				$dData = [
					'name_en' => $request->name_en,
					'name_ar' => $request->name_ar,
					'image' => $fileName,
				];

				Product::create($dData);
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
			'name_en' => 'required',
			'name_ar' => 'required',
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
					if (!Storage::disk('public')->has('products/' . date('Y') . "/" . date("m") . "/" . date("d") . "/")) {
						Storage::disk('public')->makeDirectory('products/' . date('Y') . "/" . date("m") . "/" . date("d") . "/");
					}
					Image::make($file)->resize(512, null, function ($constraint) {
						$constraint->aspectRatio();
					})->save(storage_path('app/public/products/' . $fileName));


					$d = Product::find($request->id);
					if ($d) {
						$dData = [
							'name_en' => $request->name_en,
							'name_ar' => $request->name_ar,
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

			$d = Product::find($request->id);
			if ($d) {
				$dData = [
					'name_en' => $request->name_en,
					'name_ar' => $request->name_ar,
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
		$data = Product::find($request->id);

        $data->delete();
	}
}

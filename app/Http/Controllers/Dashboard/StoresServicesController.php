<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\StoresService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoresServicesController extends Controller
{

	public function fetchAll()
	{
		$data = StoresService::orderBy('id', 'desc')->paginate(25);

        foreach($data->getCollection() as $d){
             $d->service_name = Service::find($d->service_id)->name_en;
             $d->store_name = User::find($d->store_id)->name;
             $d->product_name = Product::find($d->product_id)->name_en;
             $d->product_image = url("storage/products") . "/" . Product::find($d->product_id)->image;
        }

		return response()->json($data, 200);
	}

	public function fetchByStore($id)
	{
		$data = StoresService::where('store_id', $id)->orderBy('id', 'desc')->paginate(25);

        foreach($data->getCollection() as $d){
             $d->service_name = Service::find($d->service_id)->name_en;
             $d->store_name = User::find($d->store_id)->name;
             $d->product_name = Product::find($d->product_id)->name_en;
			 $d->product_image = url("storage/products") . "/" . Product::find($d->product_id)->image;
        }

		return response()->json($data, 200);
	}

	public function fetchImage($select)
	{
		$data = Product::where('id', $select)->orderBy('id', 'desc')->paginate(25);
		foreach($data->getCollection() as $d){
			 $d->product_image = url("storage/products") . "/" . Product::find($select)->image;
		}

		return response()->json($data, 200);
	}

	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'store_id' => 'required',
			'product_id' => 'required',
			'service_id' => 'required',
			'price' => 'required',
			
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$dData = [
			'store_id' => $request->store_id,
			'product_id' => $request->product_id,
			'service_id' => $request->service_id,
			'price' => $request->price,
			
		];

		StoresService::create($dData);
		return response()->json([
			'status' => 200,
		]);
	}


	public function update(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'store_id' => 'required',
			'product_id' => 'required',
			'service_id' => 'required',
			'price' => 'required',
			
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$d = StoresService::find($request->id);
		if ($d) {
			$dData = [
				'store_id' => $request->store_id,
			    'product_id' => $request->product_id,
			    'service_id' => $request->service_id,
			    'price' => $request->price,
				
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


	public function delete(Request $request)
	{
		$data = StoresService::find($request->id);

        $data->delete();
	}

}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Service;
use App\Models\Order;
use App\Models\StoresService;
use App\Models\CartItemService;
use App\Models\OrderItemsService;
use App\Models\OrderItemStatusLog;
use App\Models\Rating;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OrderItemsController extends Controller
{

	public function fetchAll($id)
	{
				// $data = OrderItem::orderBy('id', 'desc')->where('order_id', $id)->paginate(25);

		$data = OrderItemsService::join('order_items', 'order_items_services.order_item_id', 'order_items.id')
			->join('orders', 'order_items.order_id', 'orders.id')
			->select('order_items_services.*', 'order_items.*', 'orders.is_paid')
			->orderBy('order_items.id', 'desc')
			->where('order_items.order_id', $id)
			->where('orders.is_paid', 1)
			->paginate(25);



		foreach ($data->getCollection() as $d) {

			$d->service_name = Service::find($d->service_id)->name_en;

			$d->product_name = Product::find($d->product_id)->name_en;

			$d->store_name = User::find($d->store_id)->name;

			if($d->notes == null || $d->notes == ""){
				$d->notes = "No notes";
			}

			$order = Order::find($d->order_id)->id;
			$d->name = Order::find($order)->name;
			$d->email = Order::find($order)->email;

			$s = OrderItemStatusLog::where('order_item_id', $d->order_item_id)
			->where('service_id', $d->service_id)
			->orderBy('id', 'DESC')->first();
			$d->status = $s->status;

		}

		return response()->json($data, 200);
	}


	public function fetchStoreOrderItems($id, $order_id)
	{
		$data = OrderItemsService::join('order_items', 'order_items_services.order_item_id', 'order_items.id')
		    ->join('orders', 'order_items.order_id', 'orders.id')
			->select('order_items_services.*', 'order_items.*', 'orders.is_paid')
			->orderBy('order_items.id', 'desc')
			->where('order_items.order_id', $order_id)
			->where('order_items.store_id', $id)
			->where('orders.is_paid', 1)
			->paginate(25);



		foreach ($data->getCollection() as $d) {

			$d->service_name = Service::find($d->service_id)->name_en;

			$d->product_name = Product::find($d->product_id)->name_en;

			$d->store_name = User::find($d->store_id)->name;

			if($d->notes == null || $d->notes == ""){
				$d->notes = "No notes";
			}

			$order = Order::find($d->order_id)->id;
			$d->name = Order::find($order)->name;
			$d->email = Order::find($order)->email;

			$s = OrderItemStatusLog::where('order_item_id', $d->order_item_id)
			->where('service_id', $d->service_id)
			->orderBy('id', 'DESC')->first();
			$d->status = $s->status;
		}

		return response()->json($data, 200);
	}


	public function update(Request $request)
	{


		$validator = Validator::make($request->all(), [
			'order_item_id' => 'required',
			'service_id' => 'required',
			'status' => 'required',

		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}


		$dData = [
			'order_item_id' => $request->order_item_id,
			'service_id' => $request->service_id,
			'status' => $request->status,
		];

		OrderItemStatusLog::create($dData);


		$dd = OrderItemsService::where('order_item_id', $request->order_item_id)->where('service_id', $request->service_id)->first();
		if ($dd) {
			$ddData = [
				'status' => $request->status,
			];

			$dd->update($ddData);

			return response()->json([
				'status' => 200,
			]);
		} else {
			return response()->json([
				'message' => $request->id . ' Not found',
				'status' => 401,
			]);
		}


		return response()->json([
			'status' => 200,
		]);

	}



	public function delete(Request $request)
	{

		$orderItemServices = OrderItemsService::where('order_item_id', $request->id)
		->where('service_id', $request->service_id)
		->get();

		foreach($orderItemServices as $orderItemService){

			$orderItemService->delete();

		}
		

		$orderItemStatuses = OrderItemStatusLog::where('order_item_id', $request->id)
		->where('service_id', $request->service_id)
		->get();

		foreach($orderItemStatuses as $orderItemStatus){

			$orderItemStatus->delete();

		}


		$orderItem = OrderItem::find($request->id);

        $orderItem->delete();
		
	}

}

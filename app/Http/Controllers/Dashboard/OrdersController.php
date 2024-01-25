<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemsService;
use App\Models\OrderItemStatusLog;
use App\Models\StoresService;
use App\Models\User;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{

	public function fetchAll()
	{
		$data = Order::where('is_paid', 1)->orderBy('id', 'desc')->paginate(25);

		foreach ($data->getCollection() as $d) {
			if ($d->address_id != null) {
				$address = Address::find($d->address_id);
				$d->country = $address->country;
				$d->city = $address->city;
				$d->street = $address->street;
				$d->building = $address->building;
				$d->floor = $address->floor;
				$d->flat = $address->flat;
			}
		}

		return response()->json($data, 200);
	}


	public function fetchByStore($id)
	{
		$data = Order::where('store_id', $id)->where('is_paid', 1)->orderBy('id', 'desc')->paginate(25);

		return response()->json($data, 200);
	}


	public function update(Request $request)
	{


		$validator = Validator::make($request->all(), [
			'status' => 'required',

		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$d = Order::find($request->id);
		if ($d) {
			$dData = [
				'status' => $request->status,

			];

			$d->update($dData);

			//send email------------------------------------------------------------

			$order_id = $d->id;
			$order_Details = Order::where('id', $order_id)->first();
			$store = User::where('id', $order_Details->store_id)->first();
			$orderItems = OrderItem::where("order_id", $order_id)->get();
			// return $orderItems;
			// $user = User::where('id', $order_Details->user_id)->first();
			$user = Order::where('id', $order_id)->first();

			$items = [];

			foreach ($orderItems as $orderItem) {
				$product = Product::find($orderItem->product_id);
				$services = OrderItemsService::where('order_item_id', $orderItem->id)->get();

				$item = [
					'product_name' => $product->name_en,
					'quantity' => $orderItem->qty,
					// 'price' => 'AED ' . $orderItem->price,
					'services' => [],
				];

				foreach ($services as $service) {
					$serviceRecord = Service::find($service->service_id);
					if ($serviceRecord) {
						$item['services'][] = $serviceRecord->name_en;
					}
				}

				$items[] = $item;
			}

			$order = [
				'order_id' => $order_Details->id,
				'date' => $order_Details->created_at->format('y-m-d'),
				'customer' =>  $user->name,
				'email' =>  $user->email,
				'status' =>  $user->status,
				'contact' => $user->phone,
				'storename' => $store->name,
				'items' => $items,
				// 'total_amount' => 'AED ' . $order_Details->total,
				// 'amount' => 'AED ' . $order_Details->grand_total,
				// 'vat' => 'AED ' . $order_Details->vat
			];



			// Send the invoice email
			Mail::send('emails.update', ['order' => $order], function ($message) use ($order) {
				// $message->to('usamatariq747@gmail.com');
				$message->to($order['email']);
				$message->subject('Update for Order ' . $order['order_id']);
			});

			// return response()->json(['message' => 'Invoice email sent successfully']);


			//email sent------------------------------------------------



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
		$data = Order::find($request->id);

		$data->delete();
	}
}

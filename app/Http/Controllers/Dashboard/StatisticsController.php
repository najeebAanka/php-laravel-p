<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class StatisticsController extends Controller
{

	public function fetchAll()
	{
		$users_count = User::where('user_type', 'customer')->get()->count();

		// $orders = Order::all();
		$orders = Order::where('is_paid', 1)->get();

		$orders_count = $orders->count();

		// $sales_amount = $orders->sum('total');
		$sales_amount = $orders->sum('grand_total');

		$data = new \stdClass();

		$data->users = $users_count;

		$data->orders = $orders_count;

		$data->sales = $sales_amount;

		return response()->json($data, 200);
	}


	public function fetchByDate(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'start_date' => 'required',
			'end_date' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$startDate = date('Y-m-d', strtotime($request->start_date));
        $endDate = date('Y-m-d', strtotime($request->end_date));

		$users_count = User::where('user_type', 'customer')->whereBetween('created_at', [$startDate, $endDate])->get()->count();

	    // $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
	    $orders = Order::where('is_paid', 1)->whereBetween('created_at', [$startDate, $endDate])->get();

		$orders_count = $orders->count();

		// $sales_amount = $orders->sum('total');
		$sales_amount = $orders->sum('grand_total');

		$data = new \stdClass();

		$data->users = $users_count;

		$data->orders = $orders_count;

		$data->sales = $sales_amount;

		$data->start_date = $request->start_date;

		$data->end_date = $request->end_date;

		return response()->json($data, 200);
	}


}

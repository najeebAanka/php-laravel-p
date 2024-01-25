<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemsService;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function getPendingOrders(Request $request)
    {
        $user_id = $request->input('user_id') ?? null;
        $device_id = $request->input('device_id') ?? null;

        if ($user_id) {
            // Retrieve pending orders for the given user
            $orders = Order::where('user_id', $user_id)
                ->where('status', 'pending')
                ->get();

            if ($orders->isEmpty()) {
                return $this->formResponse("No pending orders found for the user ID", null, 404);
            }

            $ordersWithInformation = [];
            foreach ($orders as $order) {
                $cart_id = $order->cart_id;

                // Retrieve cart items for the current order
                $cartItems = CartItem::where('cart_id', $cart_id)->get();

                $orderInformation = [];
                foreach ($cartItems as $item) {
                    $store_id = $item->store_id;
                    $store = User::find($store_id);

                    if ($store) {
                        $storeName = $store->name;
                        $storeImage = $store->buildImage();

                        // Filter out cart items from different stores
                        if ($store_id !== $store->id) {
                            continue; // Skip cart item if it belongs to a different store
                        }

                        $productName = Product::where('id', $item->product_id)->value('name_en');
                        $productImage = Product::where('id', $item->product_id)->first();
                        $orderItemServices = [];
                        foreach ($item->services as $service) {
                            $serviceName = Service::where('id', $service->service_id)->value('name_en');
                            if (!in_array($serviceName, $orderItemServices)) {
                                $orderItemServices[] = $serviceName;
                            }
                        }
                        $quantity = $item->qty;
                        $totalPrice = $item->price;

                        $orderItems = OrderItem::where('store_id', $store_id)
                            ->where('order_id', $order->id)
                            ->where('product_id', $item->product_id)
                            ->get();

                        foreach ($orderItems as $value) {
                            $orderInformation[] = [
                                'item_name' => $productName,
                                'image' => $productImage->buildImage(),
                                'quantity' => $value->qty,
                                'total_price' => $value->price,
                                'services' => $orderItemServices,
                            ];
                        }
                    }
                }

                if (!empty($orderInformation)) {
                    // $store_id = $cartItems[0]->store_id;

                    $store_id = $order->store_id;

                    $store = User::where('id', $store_id)->first();
                    $storeName = $store->name;
                    $storeImage = $store->buildImage();

                    $ordersWithInformation[] = [
                        'store_id' => $store->id,
                        'store_name' => $storeName,
                        'store_image' => $storeImage,
                        'order_id' => $order->id,
                        'vat' => $order->vat,
                        'grand_total' => $order->grand_total,
                        'total' => $order->total,
                        'order_information' => $orderInformation,
                    ];
                }
            }

            return $this->formResponse("operation successful", $ordersWithInformation, 200);
        } else {
            // Retrieve pending orders for the given user
            $orders = Order::where('device_id', $device_id)
                ->where('status', 'pending')
                ->get();

            if ($orders->isEmpty()) {
                return $this->formResponse("No pending orders found for the user ID", null, 404);
            }

            $ordersWithInformation = [];
            foreach ($orders as $order) {
                $cart_id = $order->cart_id;

                // Retrieve cart items for the current order
                $cartItems = CartItem::where('cart_id', $cart_id)->get();

                $orderInformation = [];
                foreach ($cartItems as $item) {
                    $store_id = $item->store_id;
                    $store = User::find($store_id);

                    if ($store) {
                        $storeName = $store->name;
                        $storeImage = $store->buildImage();

                        // Filter out cart items from different stores
                        if ($store_id !== $store->id) {
                            continue; // Skip cart item if it belongs to a different store
                        }

                        $productName = Product::where('id', $item->product_id)->value('name_en');
                        $productImage = Product::where('id', $item->product_id)->first();
                        $orderItemServices = [];
                        foreach ($item->services as $service) {
                            $serviceName = Service::where('id', $service->service_id)->value('name_en');
                            if (!in_array($serviceName, $orderItemServices)) {
                                $orderItemServices[] = $serviceName;
                            }
                        }
                        $quantity = $item->qty;
                        $totalPrice = $item->price;

                        $orderItems = OrderItem::where('store_id', $store_id)
                            ->where('order_id', $order->id)
                            ->where('product_id', $item->product_id)
                            ->get();

                        foreach ($orderItems as $value) {
                            $orderInformation[] = [
                                'item_name' => $productName,
                                'image' => $productImage->buildImage(),
                                'quantity' => $value->qty,
                                'total_price' => $value->price,
                                'services' => $orderItemServices,
                            ];
                        }
                    }
                }

                if (!empty($orderInformation)) {
                    // $store_id = $cartItems[0]->store_id;

                    $store_id = $order->store_id;

                    $store = User::where('id', $store_id)->first();
                    $storeName = $store->name;
                    $storeImage = $store->buildImage();

                    $ordersWithInformation[] = [
                        'store_id' => $store->id,
                        'store_name' => $storeName,
                        'store_image' => $storeImage,
                        'order_id' => $order->id,
                        'vat' => $order->vat,
                        'grand_total' => $order->grand_total,
                        'total' => $order->total,
                        'order_information' => $orderInformation,
                    ];
                }
            }

            return $this->formResponse("operation successful", $ordersWithInformation, 200);
        }
    }


    public function orderHistory(Request $request)
    {

        $user_id = $request->input('user_id') ?? null;
        $device_id = $request->input('device_id') ?? null;

        if ($user_id) {
            // Retrieve complete orders for the given user
            $orders = Order::where('user_id', $user_id)
                ->where('status', 'complete')
                ->get();

            if ($orders->isEmpty()) {
                return $this->formResponse("No complete orders found for the user ID", null, 404);
            }

            $ordersWithInformation = [];
            foreach ($orders as $order) {
                $cart_id = $order->cart_id;

                // Retrieve cart items for the current order
                $cartItems = CartItem::where('cart_id', $cart_id)->get();

                $orderInformation = [];
                foreach ($cartItems as $item) {
                    $store_id = $item->store_id;
                    $store = User::find($store_id);

                    if ($store) {
                        $storeName = $store->name;
                        $storeImage = $store->buildImage();

                        // Filter out cart items from different stores
                        if ($store_id !== $store->id) {
                            continue; // Skip cart item if it belongs to a different store
                        }

                        $productName = Product::where('id', $item->product_id)->value('name_en');
                        $productImage = Product::where('id', $item->product_id)->first();
                        $orderItemServices = [];
                        foreach ($item->services as $service) {
                            $serviceName = Service::where('id', $service->service_id)->value('name_en');
                            if (!in_array($serviceName, $orderItemServices)) {
                                $orderItemServices[] = $serviceName;
                            }
                        }
                        $quantity = $item->qty;
                        $totalPrice = $item->price;

                        $orderItems = OrderItem::where('store_id', $store_id)
                            ->where('order_id', $order->id)
                            ->where('product_id', $item->product_id)
                            ->get();

                        foreach ($orderItems as $value) {
                            $orderInformation[] = [
                                'item_name' => $productName,
                                'image' => $productImage->buildImage(),
                                'quantity' => $value->qty,
                                'total_price' => $value->price,
                                'services' => $orderItemServices,
                            ];
                        }
                    }
                }

                if (!empty($orderInformation)) {
                    $store_id = $order->store_id;
                    $store = User::where('id', $store_id)->first();
                    $storeName = $store->name;
                    $storeImage = $store->buildImage();

                    $ordersWithInformation[] = [
                        'store_id' => $store->id,
                        'store_name' => $storeName,
                        'store_image' => $storeImage,
                        'order_id' => $order->id,
                        'vat' => $order->vat,
                        'grand_total' => $order->grand_total,
                        'order_information' => $orderInformation,
                    ];
                }
            }

            return $this->formResponse("Operation successful", $ordersWithInformation, 200);
        } else {
            // Retrieve complete orders for the given user
            $orders = Order::where('device_id', $device_id)
                ->where('status', 'complete')
                ->get();

            if ($orders->isEmpty()) {
                return $this->formResponse("No complete orders found for the user ID", null, 404);
            }

            $ordersWithInformation = [];
            foreach ($orders as $order) {
                $cart_id = $order->cart_id;

                // Retrieve cart items for the current order
                $cartItems = CartItem::where('cart_id', $cart_id)->get();

                $orderInformation = [];
                foreach ($cartItems as $item) {
                    $store_id = $item->store_id;
                    $store = User::find($store_id);

                    if ($store) {
                        $storeName = $store->name;
                        $storeImage = $store->buildImage();

                        // Filter out cart items from different stores
                        if ($store_id !== $store->id) {
                            continue; // Skip cart item if it belongs to a different store
                        }

                        $productName = Product::where('id', $item->product_id)->value('name_en');
                        $productImage = Product::where('id', $item->product_id)->first();
                        $orderItemServices = [];
                        foreach ($item->services as $service) {
                            $serviceName = Service::where('id', $service->service_id)->value('name_en');
                            if (!in_array($serviceName, $orderItemServices)) {
                                $orderItemServices[] = $serviceName;
                            }
                        }
                        $quantity = $item->qty;
                        $totalPrice = $item->price;

                        $orderItems = OrderItem::where('store_id', $store_id)
                            ->where('order_id', $order->id)
                            ->where('product_id', $item->product_id)
                            ->get();

                        foreach ($orderItems as $value) {
                            $orderInformation[] = [
                                'item_name' => $productName,
                                'image' => $productImage->buildImage(),
                                'quantity' => $value->qty,
                                'total_price' => $value->price,
                                'services' => $orderItemServices,
                            ];
                        }
                    }
                }

                if (!empty($orderInformation)) {
                    $store_id = $order->store_id;
                    $store = User::where('id', $store_id)->first();
                    $storeName = $store->name;
                    $storeImage = $store->buildImage();

                    $ordersWithInformation[] = [
                        'store_id' => $store->id,
                        'store_name' => $storeName,
                        'store_image' => $storeImage,
                        'order_id' => $order->id,
                        'vat' => $order->vat,
                        'grand_total' => $order->grand_total,
                        'total' => $order->total,
                        'order_information' => $orderInformation,
                    ];
                }
            }

            return $this->formResponse("Operation successful", $ordersWithInformation, 200);
        }
    }

    // public function sendInvoiceEmail(Request $request)
    // {
    //     // Retrieve order and customer data from the request or database
    //     // $order_id = $request->orderid;
    //     $order_id = 19;
    //     $order_Details = Order::where('id', $order_id)->first();
    //     $order = OrderItem::where("order_id", "19")->get();
    //     return $order;
    //     $user = User::where('id', $order_Details->user_id)->first();
    //     $order = [

    //         'order_id' => 'INV-' . $order_Details->id,
    //         'date' => $order_Details->created_at->format('y-m-d'),
    //         'customer' =>  $user->name,

    //         'items' => [
    //             [
    //                 'product_name' => 'Product A',
    //                 'quantity' => 2,
    //                 'price' => '$10.00',
    //             ],
    //             [
    //                 'product_name' => 'Product B',
    //                 'quantity' => 1,
    //                 'price' => '$20.00',
    //             ],
    //         ],
    //         'total_amount' => '$40.00',
    //     ];
    //     return $order;
    //     // Send the invoice email
    //     Mail::send('emails.invoice', ['order' => $order], function ($message) use ($order) {
    //         $message->to('usamatariq747@gmail.com');
    //         $message->subject('Invoice for Order ' . $order['order_id']);
    //     });

    //     return response()->json(['message' => 'Invoice email sent successfully']);
    // }

    public function sendInvoiceEmail(Request $request)
    {
        // Retrieve order and customer data from the request or database
        // $order_id = $request->orderid;
        $order_id = 19;
        $order_Details = Order::where('id', $order_id)->first();
        $store = User::where('id', $order_Details->store_id)->first();
        $orderItems = OrderItem::where("order_id", $order_id)->get();
        // return $orderItems;
        $user = User::where('id', $order_Details->user_id)->first();

        $items = [];

        foreach ($orderItems as $orderItem) {
            $product = Product::find($orderItem->product_id);
            $services = OrderItemsService::where('order_item_id', $orderItem->id)->get();

            $item = [
                'product_name' => $product->name_en,
                'quantity' => $orderItem->qty,
                'price' => 'AED ' . $orderItem->price,
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
            'order_id' => 'INV-' . $order_Details->id,
            'date' => $order_Details->created_at->format('y-m-d'),
            'customer' =>  $user->name,
            'contact' => $user->phone,
            'storename' => $store->name,
            'items' => $items,
            'total_amount' => 'AED ' . $order_Details->total,
            'amount' => 'AED ' . $order_Details->grand_total,
            'vat' => 'AED ' . $order_Details->vat
        ];

        // Send the invoice email
        Mail::send('emails.invoice', ['order' => $order], function ($message) use ($order) {
            $message->to('usamatariq747@gmail.com');
            $message->subject('Invoice for Order ' . $order['order_id']);
        });

        return response()->json(['message' => 'Invoice email sent successfully']);
    }


    public function cancelOrder(Request $request)
    {
        $orderIds = $request->input('order_ids');

        // Find the orders with the given IDs
        $orders = Order::whereIn('id', $orderIds)->get();

        // Cancel each order
        foreach ($orders as $order) {
            // Set the status of the order to 'cancelled'
            $order->status = 'cancelled';

            // Save the changes to the order
            $order->save();
        }

        return $this->formResponse("Operation successful", null, 200);
    }









    function orderTracking(Request $request)
    {

        try {
            $user_id = $request->user_id ?? null;
            $device_id = $request->device_id ?? null;

            $orders = Order::where('status', '!=', 'complete')
                ->when($user_id, function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })
                ->when($device_id, function ($query) use ($device_id) {
                    $query->where('device_id', $device_id);
                })
                ->with([
                    'orderItems.orderItemServices.service', // Load the service relationship
                    'orderItems.orderItemStatusLog',
                    'user',
                ])
                ->get();

            // Append the status, service name, and image to each service in the order_item_services array
            foreach ($orders as $order) {
                foreach ($order->orderItems as $orderItem) {
                    foreach ($orderItem->orderItemServices as $service) {
                        // Retrieve the service status from the order_item_services table
                        $orderItemStatus = OrderItemsService::where('order_item_id', $service->order_item_id)->where('service_id', $service->service_id)->first();
                        $serviceStatus = $orderItemStatus->status;

                        // Retrieve the service name and image from the service relationship
                        $serviceName = $service->service->name_en ?? null;
                        $serviceImage = $service->service->buildImage() ?? null;

                        $service->status = $serviceStatus;
                        $service->name = $serviceName;
                        $service->image = $serviceImage;

                        unset($service->service); // Remove the nested service object
                    }

                    // Get the product details for the order item
                    $product = $orderItem->product;
                    $orderItem->product_name_en = $product->name_en ?? null;
                    $orderItem->product_image = $product->buildImage() ?? null;

                    unset($orderItem->orderItemStatusLog);
                    unset($orderItem->product);
                }
            }

            // Change the user object to store
            foreach ($orders as $order) {
                $store = $order->user;
                $order->store = $store;
                unset($order->user);
            }
            return $this->formResponse("Operation successful", $orders, 200);
        } catch (\Throwable $th) {
            return $this->formResponse("Operation failed", null, 400);
        }
    }
}

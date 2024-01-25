<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CartItemsService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemsService;
use App\Models\OrderItemStatusLog;
use App\Models\OrderStatusLog;
use App\Models\Product;
use App\Models\Service;
use App\Models\StoresService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;


class CartController extends Controller
{
    // Create a new empty cart for a visitor and return the cart id

    public function createCart(Request $request)
    {
        try {
            $user = User::where('email', $request->input('email'))->first();
            $data = [];
            if ($user) {
                $cart = $user->cart()->latest()->first();
                if ($cart && $cart->status == 'checked_out') {
                    // Create a new cart
                    $newCart = new Cart;
                    $newCart->status = 'active';
                    $newCart->user()->associate($user);
                    $newCart->save();

                    $data['cart_id'] = $newCart->id;
                } else {
                    $cart = Cart::where('user_id', $user->id)->latest()->first('id');
                    $data['cart_id'] = $cart->id;
                }
            } else {
                $guestCart = Cart::where('device_id', $request->device_id)->latest()->first();
                if (!$guestCart) {
                    $guestUser = new User;
                    $guestUser->name = 'Guest';
                    $guestUser->name_ar = 'Guest';
                    $guestUser->user_type = 'guest';
                    $guestUser->email = 'guest_' . Str::random(10) . '@example.com';
                    $guestUser->password = bcrypt(Str::random(16));
                    $guestUser->save();

                    $guestCart = new Cart;
                    $guestCart->user_ip = $request->ip();
                    $guestCart->device_id = $request->device_id;
                    $guestCart->status = 'active';
                    $guestCart->user()->associate($guestUser);
                    $guestCart->save();

                    $data['cart_id'] = $guestCart->id;
                    $data['device_id'] = $guestCart->device_id;
                } else if ($guestCart->status == 'checked_out') {
                    // Create a new cart
                    $newCart = new Cart;
                    $newCart->user_ip = $request->ip();
                    $newCart->device_id = $request->device_id; // Corrected line
                    $newCart->status = 'active';
                    $newCart->user()->associate($guestCart->user);
                    $newCart->save();

                    $data['cart_id'] = $newCart->id;
                    $data['device_id'] = $newCart->device_id; // Corrected line
                } else {
                    $data['cart_id'] = $guestCart->id;
                    $data['device_id'] = $guestCart->device_id;
                }
            }

            return $this->formResponse(
                'operation successful',
                $data,
                200
            );
        } catch (\Throwable $th) {
            return $this->formResponse(
                'there is some error creating cart',
                null,
                400
            );
        }
    }


    public function addToCart(Request $request)
    {
        // try {
        $cartId = $request->input('cart_id');
        $storeId = $request->input('store_id');
        $items = $request->input('items');

        $cart = Cart::findOrFail($cartId);

        foreach ($items as $item) {
            $productId = $item['product_id'];
            $serviceIds = $item['service_id'];
            $qty = $item['qty'];
            $note = $item['note'] ?? null;

            // Calculate the total price for selected services
            $totalPrice = 0;
            $selectedServices = [];

            foreach ($serviceIds as $serviceId) {
                $storeService = StoresService::where([
                    'service_id' => $serviceId,
                    'store_id' => $storeId,
                    'product_id' => $productId
                ])->first();

                if ($storeService) {
                    $totalPrice += $storeService->price;
                    $selectedServices[] = $serviceId; // Store service_id instead of id
                }
            }

            $existingCartItem = CartItem::where([
                'cart_id' => $cartId,
                'store_id' => $storeId,
                'product_id' => $productId
            ])->first();

            if ($existingCartItem) {
                // Item already exists in the cart
                $existingServiceIds = $existingCartItem->services()->pluck('service_id')->toArray();

                if ($existingServiceIds === $serviceIds) {
                    // Services are the same, update quantity and price
                    $existingCartItem->qty += $qty;
                    $existingCartItem->price += ($totalPrice * $qty);
                    $existingCartItem->save();
                } else {
                    // Services are different, overwrite with new services
                    $existingCartItem->services()->delete();

                    foreach ($selectedServices as $serviceId) {
                        $servicePrice = StoresService::where(
                            'store_id',
                            $storeId
                        )
                            ->where('product_id', $productId)
                            ->where('service_id', $serviceId)
                            ->first('price');

                        $cartItemService = new CartItemsService();
                        $cartItemService->cart_item_id = $existingCartItem->id;
                        $cartItemService->service_id = $serviceId;
                        $cartItemService->price = $servicePrice->price;
                        $cartItemService->save();
                    }

                    // Update quantity and price
                    $existingCartItem->qty = $qty;
                    $existingCartItem->price = $totalPrice * $qty;
                    $existingCartItem->save();
                }
            } else {
                // Item doesn't exist in the cart, create a new entry
                $cartItem = new CartItem();
                $cartItem->cart_id = $cartId;
                $cartItem->store_id = $storeId;
                $cartItem->product_id = $productId;
                $cartItem->qty = $qty;
                $cartItem->price = $totalPrice * $qty;
                $cartItem->notes = $note ?? '';
                $cartItem->save();

                // Save the services for the cart item
                foreach ($selectedServices as $serviceId) {
                    $servicePrice = StoresService::where('store_id', $storeId)
                        ->where('product_id', $productId)
                        ->where('service_id', $serviceId)
                        ->first('price');

                    $cartItemService = new CartItemsService();
                    $cartItemService->cart_item_id = $cartItem->id;
                    $cartItemService->service_id = $serviceId;
                    $cartItemService->price = $servicePrice->price;
                    $cartItemService->save();
                }
            }
        }

        return $this->formResponse(
            "Items added to cart successfully",
            null,
            200
        );
        // } catch (\Throwable $th) {
        //     return $this->formResponse(
        //         "There is an error adding items to the cart",
        //         null,
        //         400
        //     );
        // }
    }


    // Delete a one/more items from the cart
    public function deleteCartItem(Request $request)
    {
        try {
            $cartItemIds = $request->input('cart_item_id', []);

            // Convert the string representation of the array to an actual array
            $cartItemIds = json_decode($cartItemIds, true);

            // Retrieve the cart items with their associated services
            $cartItems = CartItem::with('services')->whereIn('id', $cartItemIds)->get();

            // Soft delete the cart items and their associated services
            foreach ($cartItems as $cartItem) {
                // Soft delete associated services
                $cartItem->services()->delete();

                // Soft delete the cart item
                $cartItem->delete();
            }

            return $this->formResponse("Operation successful", null, 200);
        } catch (\Throwable $th) {
            return $this->formResponse("Operation failed", null, 400);
        }
    }
    // Delete multiple items from the cart

    public function deleteCartItems(Request $request)
    {
        try {
            $cartId = $request->input('cart_id');

            // Get the cart items with the specified cart_id, including soft deleted items
            $cartItems = CartItem::withTrashed()->where('cart_id', $cartId)->get();

            // Retrieve the IDs of the cart items to be deleted
            $cartItemIds = $cartItems->pluck('id');

            // Soft delete associated services
            CartItemsService::whereIn('cart_item_id', $cartItemIds)->delete();

            // Soft delete the cart items
            $cartItems->each->delete();

            return $this->formResponse("Operation successful", null, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->formResponse("Operation failed", null, 400);
        }
    }

    // public function getCartItems(Request $request, User $user)
    // {
    //     try {
    //         $cartId = $request->input('cart_id');

    //         $cartItems = CartItem::where('cart_id', $cartId)
    //             ->with('product', 'services.service')
    //             ->get();

    //         if ($cartItems->isEmpty()) {
    //             return $this->formResponse("Your cart is empty", null, 200);
    //         }

    //         $groupedCartItems = [];
    //         foreach ($cartItems as $cartItem) {
    //             if (!$cartItem->product) {
    //                 continue;
    //             }

    //             $store = $user->where('id', $cartItem->store_id)->first();
    //             // return $store;

    //             $product = [
    //                 'id' => $cartItem->product_id,
    //                 'cart_item_id' => $cartItem->id,
    //                 'store_id' => $cartItem->store_id,
    //                 'store_name' => $store->name,
    //                 'store_image' => $store->buildImage(),
    //                 'name_en' => $cartItem->product->name_en,
    //                 'name_ar' => $cartItem->product->name_ar,
    //                 'image' => $cartItem->product->buildImage(),

    //             ];

    //             $serviceData = [];
    //             $totalPrice = 0;

    //             foreach ($cartItem->services as $service) {
    //                 if (!$service->service) {
    //                     continue;
    //                 }

    //                 $storeService = StoresService::where('store_id', $cartItem->store_id)
    //                     ->where('product_id', $cartItem->product_id)
    //                     ->where('service_id', $service->service_id)
    //                     ->first();

    //                 if ($storeService) {
    //                     $servicePrice = $storeService->price;

    //                     $serviceData[] = [
    //                         'id' => $service->service_id,
    //                         'name_en' => $service->service->name_en,
    //                         'name_ar' => $service->service->name_ar,
    //                         'price' => $servicePrice,
    //                         'quantity' => $cartItem->qty,
    //                     ];

    //                     $totalPrice += $servicePrice * $cartItem->qty;
    //                 }
    //             }

    //             // Fetch all services offered by the product from the specific store
    //             $allServices = StoresService::where('store_id', $cartItem->store_id)
    //                 ->where('product_id', $cartItem->product_id)
    //                 ->pluck('service_id');

    //             // Fetch the service details for all services offered
    //             $allServicesData = Service::whereIn('id', $allServices)->get();

    //             // Append all services offered to the product with selected status
    //             $product['servicesOffered'] = $allServicesData->map(function ($service) use ($serviceData) {
    //                 $isSelected = collect($serviceData)->contains('id', $service->id) ? 1 : 0;
    //                 return [
    //                     'id' => $service->id,
    //                     'name_en' => $service->name_en,
    //                     'name_ar' => $service->name_ar,
    //                     'isSelected' => $isSelected,
    //                 ];
    //             });

    //             $storeKey = $cartItem->store_id;
    //             $productKey = $cartItem->product_id;

    //             if (isset($groupedCartItems[$storeKey][$productKey])) {
    //                 $groupedCartItems[$storeKey][$productKey]['services'] = array_merge($groupedCartItems[$storeKey][$productKey]['services'], $serviceData);
    //             } else {
    //                 $groupedCartItems[$storeKey][$productKey] = [
    //                     'product' => $product,
    //                     'quantity' => $cartItem->qty,
    //                     'total_price' => $totalPrice,
    //                 ];
    //             }
    //         }

    //         $finalCartItems = [];
    //         foreach ($groupedCartItems as $storeKey => $storeItems) {
    //             foreach ($storeItems as $productKey => $productData) {
    //                 $finalCartItems[] = [
    //                     'product' => $productData['product'],
    //                     'quantity' => $productData['quantity'],
    //                     'total_price' => $productData['total_price'],
    //                 ];
    //             }
    //         }

    //         return $this->formResponse("Operation successful", $finalCartItems, 200);
    //     } catch (\Exception $e) {
    //         // Handle the exception here
    //         return $this->formResponse("Error getting the cartItems", null, 400);
    //     }
    // }

    public function getCartItems(
        Request $request,
        User $user
    ) {
        try {
            $cartId = $request->input('cart_id');
            $device_id = Cart::where('id', $cartId)->first('device_id');
            // return $device_id;
            $user_id = Cart::where('id', $cartId)->first('user_id');

            if ($device_id) {
                $pedingOrders = Order::where('status', 'pending')->where('device_id', $device_id->device_id)->count();
            }
            $pedingOrders = Order::where('status', 'pending')->where('user_id', $user_id->user_id)->count();



            $cartItems = CartItem::where('cart_id', $cartId)
                ->whereHas('cart', function ($query) {
                    $query->where('status', 'active');
                })
                ->with('product', 'services.service')
                ->get();

            // if ($cartItems->isEmpty()) {
            //     return $this->formResponse("Your cart is empty", null, 200);
            // }

            $groupedCartItems = [];
            foreach ($cartItems as $cartItem) {
                if (!$cartItem->product) {
                    continue;
                }

                $store = $user->where('id', $cartItem->store_id)->first();

                $product = [
                    'id' => $cartItem->product_id,
                    'cart_item_id' => $cartItem->id,
                    'store_id' => $cartItem->store_id,
                    'store_name' => $store->name,
                    'store_image' => $store->buildImage(),
                    'name_en' => $cartItem->product->name_en,
                    'name_ar' => $cartItem->product->name_ar,
                    'image' => $cartItem->product->buildImage(),
                ];

                $serviceData = [];
                $totalPrice = 0;

                foreach ($cartItem->services as $service) {
                    if (!$service->service) {
                        continue;
                    }

                    $storeService = StoresService::where('store_id', $cartItem->store_id)
                        ->where('product_id', $cartItem->product_id)
                        ->where('service_id', $service->service_id)
                        ->first();

                    if ($storeService) {
                        $servicePrice = $storeService->price;

                        $serviceData[] = [
                            'id' => $service->service_id,
                            'name_en' => $service->service->name_en,
                            'name_ar' => $service->service->name_ar,
                            'price' => $servicePrice,
                            'quantity' => $cartItem->qty,
                            'isSelected' => $service->isSelected,
                        ];

                        $totalPrice += $servicePrice * $cartItem->qty;
                    }
                }

                // Check if all services have isSelected equal to 0
                $allServicesUnselected = collect($serviceData)->every(function ($service) {
                    return $service['isSelected'] === 0;
                });

                if ($allServicesUnselected) {
                    continue;
                }

                // Fetch all services offered by the product from the specific store
                $allServices = StoresService::where('store_id', $cartItem->store_id)
                    ->where('product_id', $cartItem->product_id)
                    ->pluck('service_id');

                // Fetch the service details for all services offered
                $allServicesData = Service::whereIn('id', $allServices)->get();

                // Append all services offered to the product with selected status
                $product['servicesOffered'] = $allServicesData->map(function ($service) use ($serviceData) {
                    $isSelected = collect($serviceData)->contains('id', $service->id) ? 1 : 0;
                    return [
                        'id' => $service->id,
                        'name_en' => $service->name_en,
                        'name_ar' => $service->name_ar,
                        'isSelected' => $isSelected,
                    ];
                });

                $storeKey = $cartItem->store_id;
                $productKey = $cartItem->product_id;

                if (isset($groupedCartItems[$storeKey][$productKey])) {
                    $groupedCartItems[$storeKey][$productKey]['services'] = array_merge($groupedCartItems[$storeKey][$productKey]['services'], $serviceData);
                } else {
                    $groupedCartItems[$storeKey][$productKey] = [
                        'product' => $product,
                        'quantity' => $cartItem->qty,
                        'total_price' => $totalPrice,
                    ];
                }
            }

            $finalCartItems = [];
            // $finalCartItems['pendingOrderCount'] = $pedingOrders;
            foreach ($groupedCartItems as $storeKey => $storeItems) {
                foreach ($storeItems as $productKey => $productData) {
                    $finalCartItems[] = [
                        'product' => $productData['product'],
                        'quantity' => $productData['quantity'],
                        'total_price' => $productData['total_price'],
                    ];
                }
            }
            if ($pedingOrders == Null) {
                $pedingOrders = 0;
            }

            $data =
                [
                    'pendingOrders' => $pedingOrders,
                    'cartItems' => $finalCartItems
                ];




            return $this->formResponse("Operation successful", $data, 200);
        } catch (\Exception $e) {
            // Handle the exception here
            return $this->formResponse("Error getting the cartItems", null, 400);
        }
    }

    public function getCartTotalPrice(Request $request)
    {
        $cart = Cart::find($request->input('cart_id'));
        $totalPrice = 0;

        foreach ($cart->cartItems as $item) {
            $totalPrice += $item->price;
        }

        return response()->json([
            'total_price' => $totalPrice
        ]);
    }


    public function checkout(Request $request)
    {
        // try {
        $cart = Cart::find($request->input('cart_id'));

        if (!$cart) {
            return $this->formResponse("Invalid cart ID", null, 404);
        }

        $cartItems = $cart->cartItems;

        if ($cartItems->isEmpty()) {
            return $this->formResponse("your cart is empty", null, 200);
        }

        $user = $request->input('user_id') ?? null;


        if (!$user) {
            // Create a guest user
            $user = Cart::where('device_id', $request->input('device_id'))->first();
            $orders = [];
            $orderInformation = []; // Initialize the order information array
            foreach ($cartItems as $item) {
                $storeId = $item->store_id;

                $store = User::find($storeId);
                $storeName = $store->name;
                $storeImage = $store->buildImage();
                if (!isset($orders[$storeId])) {
                    $order = new Order();
                    $order->user_id = $user['user_id'];
                    $order->device_id = $request->device_id;
                    $order->user_ip = $request->ip();
                    $order->store_id = $storeId;
                    $order->status = $request->input('status') ?? 'pending';
                    $order->cart_id = $cart->id;
                    $order->name = $request->input('name') ?? null;
                    $order->email = $request->input('email') ?? null;
                    $order->phone = $request->input('phone') ?? null;
                    $order->country = $request->input('country') ?? null;
                    $order->city = $request->input('city') ?? null;
                    $order->street = $request->input('street') ?? null;
                    $order->building = $request->input('building') ?? null;
                    $order->floor = $request->input('floor') ?? null;
                    $order->flat = $request->input('flat') ?? null;
                    $order->address_id = $request->input('address_id') ?? null;
                    $order->vat = 0;
                    $order->total = 0;
                    $order->grand_total = 0;
                    $order->is_paid = 0;
                    $order->save();

                    $orders[$storeId] = $order;
                }

                $order = $orders[$storeId];

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->store_id = $item->store_id;
                $orderItem->product_id = $item->product_id;
                $orderItem->cart_item_id = $item->id;
                $orderItem->price = $item->price;
                $orderItem->qty = $item->qty;
                $orderItem->total = $item->price;
                $orderItem->notes = $item->notes ?? null;
                $orderItem->save();

                // $orderStatusLog = new OrderStatusLog();
                // $orderStatusLog->order_id = $order->id;
                // $orderStatusLog->status = "pending";
                // $orderStatusLog->save();

                // Retrieve item name and calculate total price
                $productName = Product::where('id', $item->product_id)->value('name_en');
                $productImage = Product::where('id', $item->product_id)->first();
                $itemTotalPrice = $item->price;

                // Add relevant services to the order item
                $orderItemServices = []; // Initialize an array for storing services
                foreach ($item->services as $service) {
                    $orderItemService = new OrderItemsService();
                    $orderItemService->order_item_id = $orderItem->id;
                    $orderItemService->service_id = $service->service_id;
                    $orderItemService->price = $service->price;
                    $orderItemService->status = 'pending';

                    // Set other information related to the service
                    $orderItemService->save();

                    $orderItemStatusLog = new OrderItemStatusLog();
                    $orderItemStatusLog->order_item_id = $orderItem->id;
                    $orderItemStatusLog->service_id = $service->service_id;
                    $orderItemStatusLog->status = 'pending';
                    $orderItemStatusLog->save();

                    // Retrieve service name
                    $serviceName = Service::where('id', $service->service_id)->value('name_en');

                    // Add service name to the order item services array
                    $orderItemServices[] = $serviceName;
                }

                // Create a new order information array for each item
                $orderInformation[$order->id][] = [
                    'services' => $orderItemServices,
                    'item_name' => $productName,
                    'image' => $productImage->buildImage(),
                    'quantity' => $item->qty,
                    'total_price' => $itemTotalPrice,
                ];

                // Update the total and grand total of the order
                $order->total += $itemTotalPrice;
                $order->grand_total += $itemTotalPrice;

                $vat = $order->total * 0.05; // Calculate VAT (5%)
                $order->vat = $vat;
                $order->grand_total = $order->total + $vat;
                $order->save();


                // // Soft delete the cart item and associated services
                // $item->services()->delete();
                // $item->delete()
                $cart->status = 'checked_out';
                $cart->save();
            }

            $orderIds = Order::where('cart_id', $request->input('cart_id'))->get(['id', 'grand_total', 'vat', 'total']);

            $ordersWithInformation = [];
            foreach ($orderIds as $orderId) {
                if (!empty($orderInformation[$orderId->id])) {
                    $ordersWithInformation[] = [
                        'order_id' => $orderId->id,
                        'store_name' => $storeName,
                        'store_image' => $storeImage,
                        'total' => $orderId->total,
                        'grand_total' => $orderId->grand_total,
                        'vat' => $orderId->vat,
                        'order_information' => $orderInformation[$orderId->id],
                    ];
                }
            }

            return $this->formResponse("Operation successful", [
                'orders' => $ordersWithInformation,
            ], 200);
        } else {
            $orders = [];
            $orderInformation = []; // Initialize the order information array
            foreach ($cartItems as $item) {
                $storeId = $item->store_id;

                $store = User::find($storeId);
                $storeName = $store->name;
                $storeImage = $store->buildImage();
                if (!isset($orders[$storeId])) {
                    $order = new Order();
                    $order->user_id = $user;
                    $order->device_id = $request->device_id ?? null;
                    $order->user_ip = $request->ip();
                    $order->store_id = $storeId;
                    $order->status = $request->input('status') ?? 'pending';
                    $order->cart_id = $cart->id;
                    $order->name = $request->input('name') ?? null;
                    $order->email = $request->input('email') ?? null;
                    $order->phone = $request->input('phone') ?? null;
                    $order->country = $request->input('country') ?? null;
                    $order->city = $request->input('city') ?? null;
                    $order->street = $request->input('street') ?? null;
                    $order->building = $request->input('building') ?? null;
                    $order->floor = $request->input('floor') ?? null;
                    $order->flat = $request->input('flat') ?? null;
                    $order->address_id = $request->input('address_id') ?? null;
                    $order->vat = 0;
                    $order->total = 0;
                    $order->grand_total = 0;
                    $order->is_paid = 0;
                    $order->save();

                    $orders[$storeId] = $order;
                }

                $order = $orders[$storeId];

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->store_id = $item->store_id;
                $orderItem->product_id = $item->product_id;
                $orderItem->cart_item_id = $item->id;
                $orderItem->price = $item->price;
                $orderItem->qty = $item->qty;
                $orderItem->total = $item->price;
                $orderItem->notes = $item->notes ?? null;
                $orderItem->save();

                // $orderStatusLog = new OrderStatusLog();
                // $orderStatusLog->order_id = $order->id;
                // $orderStatusLog->status = "pending";
                // $orderStatusLog->save();

                // Create a new order item status log


                // Retrieve item name and calculate total price
                $productName = Product::where('id', $item->product_id)->value('name_en');
                $productImage = Product::where('id', $item->product_id)->first();

                $itemTotalPrice = $item->price;

                // Add relevant services to the order item
                $orderItemServices = []; // Initialize an array for storing services
                foreach ($item->services as $service) {
                    $orderItemService = new OrderItemsService();
                    $orderItemService->order_item_id = $orderItem->id;
                    $orderItemService->service_id = $service->service_id;
                    $orderItemService->price = $service->price;
                    $orderItemService->status = 'pending';

                    // Set other information related to the service
                    $orderItemService->save();

                    $orderItemStatusLog = new OrderItemStatusLog();
                    $orderItemStatusLog->order_item_id = $orderItem->id;
                    $orderItemStatusLog->service_id = $service->service_id;
                    $orderItemStatusLog->status = 'pending';
                    $orderItemStatusLog->save();

                    // Retrieve service name
                    $serviceName = Service::where('id', $service->service_id)->value('name_en');

                    // Add service name to the order item services array
                    $orderItemServices[] = $serviceName;
                }

                // Create a new order information array for each item
                $orderInformation[$order->id][] = [
                    'services' => $orderItemServices,
                    'item_name' => $productName,
                    'image' => $productImage->buildImage(),
                    'quantity' => $item->qty,
                    'total_price' => $itemTotalPrice,
                ];

                // Update the total and grand total of the order
                $order->total += $itemTotalPrice;
                $order->grand_total += $itemTotalPrice;

                $vat = $order->total * 0.05; // Calculate VAT (5%)
                $order->vat = $vat;
                $order->grand_total = $order->total + $vat;
                $order->save();


                // // Soft delete the cart item and associated services
                // $item->services()->delete();
                // $item->delete()

                $cartStatus = Cart::where('id', $request->cart_id)->first();
                $cartStatus->status = 'checked_out';
                $cartStatus->save();
            }

            $orderIds = Order::where('cart_id', $request->input('cart_id'))->get(['id', 'grand_total', 'vat', 'total']);

            $ordersWithInformation = [];
            foreach ($orderIds as $orderId) {
                if (!empty($orderInformation[$orderId->id])) {
                    $ordersWithInformation[] = [
                        'order_id' => $orderId->id,
                        'store_name' => $storeName,
                        'store_image' => $storeImage,
                        'total' => $orderId->total,
                        'grand_total' => $orderId->grand_total,
                        'vat' => $orderId->vat,
                        'order_information' => $orderInformation[$orderId->id],
                    ];
                }
            }

            return $this->formResponse("Operation successful", [
                'orders' => $ordersWithInformation,
            ], 200);
        }
        // } catch (\Throwable $th) {
        //     return $this->formResponse("Operation failed", null, 400);
        // }
    }

    public function getUserOrders(Request $request)
    {
        try {
            $orders = Order::where('cart_id', $request->cart_id)->get();
            if (!$orders) {
                return $this->formResponse(
                    "no orders found",
                    null,
                    200
                );
            }
            return $this->formResponse("operation successful", [
                'orders' => $orders
            ], 200);
        } catch (\Exception $e) {
            // Handle the exception here
            return $this->formResponse("operation failed", null, 400);
        }
    }

    public function getOrderItems(Request $request, $orderId)
    {
        $order = Order::find($orderId);

        return response()->json([
            'items' => $order->order_items
        ]);
    }


    public function changeCartItemServices(Request $request)
    {
        try {
            $cartItemId = $request->input('cart_item_id');
            $isSelected = $request->input('isSelected');
            $serviceId = $request->input('service_id');
            $productId = $request->input('product_id');
            $storeId = $request->input('store_id');

            $cartItem = CartItem::find($cartItemId);
            if (!$cartItem) {
                return $this->formResponse("Cart item not found", null, 404);
            }

            if ($isSelected == 1) {
                // Check if the service is already added to the cart item
                $existingService = CartItemsService::where('cart_item_id', $cartItemId)
                    ->where('service_id', $serviceId)
                    ->first();

                if ($existingService) {
                    return $this->formResponse("Service already added to cart item", null, 400);
                }

                // Get the price of the service
                $service = StoresService::where('store_id', $storeId)
                    ->where('product_id', $productId)
                    ->where('service_id', $serviceId)
                    ->first();

                if (!$service) {
                    return $this->formResponse("Service not found", null, 404);
                }

                // Create a new cart item service
                $CartItemsService = new CartItemsService();
                $CartItemsService->cart_item_id = $cartItemId;
                $CartItemsService->service_id = $serviceId;
                $CartItemsService->price = $service->price;
                $CartItemsService->save();

                // Update the cart item price
                $cartItem->price += $service->price * $cartItem->qty;
                $cartItem->save();
            } else {
                // Delete the cart item service
                $service = CartItemsService::where('cart_item_id', $cartItemId)
                    ->where('service_id', $serviceId)
                    ->first();

                if (!$service) {
                    return $this->formResponse("Service not found in cart item", null, 400);
                }

                // Subtract the price of the service multiplied by the cart item quantity from the cart item price
                $cartItem->price -= $service->price * $cartItem->qty;
                $cartItem->save();

                $service->delete();
            }

            return $this->formResponse("Services updated successfully", null, 200);
        } catch (\Exception $e) {
            // Handle the exception here
            return $this->formResponse("Error updating the services", null, 400);
        }
    }


    public function changeCartItemQuantity(Request $request)
    {
        try {
            $cartItemId = $request->input('cart_item_id');
            $qty = $request->input('qty');

            $cartItem = CartItem::find($cartItemId);
            if (!$cartItem) {
                return $this->formResponse("Cart item not found", null, 404);
            }

            $cartItem->qty = $qty;

            $cartItem->save();

            // Get the services for the cart item
            $services = $cartItem->services;

            // Calculate the new price for the cart item
            $newPrice = 0;
            foreach ($services as $service) {
                $newPrice += $service->price * $qty;
            }

            // Update the price for the cart item
            $cartItem->price = $newPrice;
            $cartItem->save();

            return $this->formResponse("Quantity updated successfully", null, 200);
        } catch (\Exception $e) {
            // Handle the exception here
            return $this->formResponse("Error updating the quantity", null, 400);
        }
    }


    public function updateNote(Request $request)
    {
        try {
            $cartItemId = $request->input('cart_item_id');
            $notes = $request->input('notes');

            $cartItem = CartItem::find($cartItemId);

            if (!$cartItem) {
                return $this->formResponse("Cart item not found", null, 404);
            }

            $cartItem->notes = $notes;
            $cartItem->save();

            return $this->formResponse("Notes updated successfully", $cartItem, 200);
        } catch (\Exception $e) {
            // Handle the exception here
            return $this->formResponse("Error updating the notes", null, 400);
        }
    }
}

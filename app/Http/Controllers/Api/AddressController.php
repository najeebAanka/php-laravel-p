<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function addAddress(Request $request)
    {
        // Validate the request data
        $request->validate([
            'country' => 'required',
            'city' => 'required',
            'street' => 'required',
            'building' => 'nullable',
            'floor' => 'nullable',
            'flat' => 'nullable',
        ]);

        // Create a new address model
        $address = new Address();

        // Set the address properties from the request data
        // $address->user_id = auth()->user()->id;
        $address->user_id = $request->user_id;
        $address->country = $request->get('country');
        $address->city = $request->get('city');
        $address->street = $request->get('street');
        $address->building = $request->get('building');
        $address->floor = $request->get('floor');
        $address->flat = $request->get('flat');
        $address->is_default = $request->get('is_default', 0);

        // Save the address model
        $address->save();


        // Return the address model
        return $address;
    }


    public function getAddress(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required'
        ]);

        $addresses = Address::where('user_id', $request->user_id)->get();
        // Return the address model
        return $this->formResponse('operation successful', $addresses, 200);
    }

    // public function orderAddress(Request $request)
    // {
    //     // Validate the request data
    //     $request->validate([
    //         'address_id' => 'nullable|exists:addresses,id',
    //         'country' => 'required',
    //         'city' => 'required',
    //         'street' => 'required',
    //         'building' => 'nullable',
    //         'floor' => 'nullable',
    //         'flat' => 'nullable',
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'phone' => 'required',
    //     ]);

    //     // Check if an address ID is provided
    //     if ($request->has('address_id')) {
    //         // Retrieve the address from the addresses table
    //         $address = Address::find($request->address_id);

    //         if ($address) {
    //             // Address exists, update the orders table with the address details
    //             Order::where('id', $request->order_id)
    //                 ->update([
    //                     'name' => $address->name,
    //                     'email' => $address->email,
    //                     'phone' => $address->phone,
    //                     'country' => $address->country,
    //                     'city' => $address->city,
    //                     'street' => $address->street,
    //                     'building' => $address->building,
    //                     'floor' => $address->floor,
    //                     'flat' => $address->flat,
    //                     'address_id' => $address->id,
    //                 ]);
    //         }
    //     }

    //     // If address_id is not provided or the address doesn't exist, save the address info given by the user
    //     if (!isset($address)) {
    //         $address = new Address();

    //         // Set the address properties from the request data
    //         $address->user_id = $request->user_id;
    //         $address->country = $request->get('country');
    //         $address->city = $request->get('city');
    //         $address->street = $request->get('street');
    //         $address->building = $request->get('building');
    //         $address->floor = $request->get('floor');
    //         $address->flat = $request->get('flat');
    //         $address->is_default = $request->get('is_default', 0);

    //         // Save the address model
    //         $address->save();

    //         // Update the orders table with the new address details
    //         Order::where('id', $request->order_id)
    //             ->update([
    //                 'name' => $request->get('name'),
    //                 'email' => $request->get('email'),
    //                 'phone' => $request->get('phone'),
    //                 'country' => $address->country,
    //                 'city' => $address->city,
    //                 'street' => $address->street,
    //                 'building' => $address->building,
    //                 'floor' => $address->floor,
    //                 'flat' => $address->flat,
    //                 'address_id' => $address->id,
    //             ]);
    //     }

    //     // Return the address model
    //     return response()->json($address);
    // }

    public function orderAddress(Request $request)
    {
        // Validate the request data
        $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            'name' => $request->has('address_id') ? 'nullable' : 'required',
            'email' => $request->has('address_id') ? 'nullable' : 'required|email',
            'phone' => $request->has('address_id') ? 'nullable' : 'required',
            'country' => $request->has('address_id') ? 'nullable' : 'required',
            'city' => $request->has('address_id') ? 'nullable' : 'required',
            'street' => $request->has('address_id') ? 'nullable' : 'required',
            'building' => 'nullable',
            'floor' => 'nullable',
            'flat' => 'nullable',
        ]);

        // Check if an address ID is provided
        if ($request->has('address_id')) {
            // Retrieve the address from the addresses table
            $address = Address::find($request->address_id);
            $user_id = Order::where('id', $request->order_id)->first('user_id');
            // return $user_id;
            $UserData = User::where('id', $user_id->user_id)->first();

            if ($address) {
                // Address exists, update the orders table with the address details
                Order::where('id', $request->order_id)
                    ->update([
                        'name' => $UserData->name,
                        'email' => $UserData->email,
                        'phone' => $UserData->phone,
                        'country' => $address->country,
                        'city' => $address->city,
                        'street' => $address->street,
                        'building' => $address->building,
                        'floor' => $address->floor,
                        'flat' => $address->flat,
                        'address_id' => $address->id,
                    ]);
            }
        }

        // If address_id is not provided or the address doesn't exist, save the address info given by the user
        else if (!isset($address) && $request->user_id) {
            $address = new Address();

            // Set the address properties from the request data

            $address->user_id = $request->user_id;
            $address->name = $request->get('name');
            $address->email = $request->get('email');
            $address->phone = $request->get('phone');
            $address->country = $request->get('country');
            $address->city = $request->get('city');
            $address->street = $request->get('street');
            $address->building = $request->get('building');
            $address->floor = $request->get('floor');
            $address->flat = $request->get('flat');
            $address->is_default = $request->get('is_default', 0);

            // Save the address model
            $address->save();

            // Update the orders table with the new address details
            $user_id = Order::where('id', $request->order_id)->first('user_id');
            $UserData = User::where('id', $user_id->user_id)->first();

            Order::where('id', $request->order_id)
                ->update([
                    'name' => $UserData->name,
                    'email' => $UserData->email,
                    'phone' => $UserData->phone,
                    'country' => $address->country,
                    'city' => $address->city,
                    'street' => $address->street,
                    'building' => $address->building,
                    'floor' => $address->floor,
                    'flat' => $address->flat,
                    'address_id' => $address->id,
                ]);
        } else {
            Order::where('id', $request->order_id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'country' => $request->country,
                    'city' => $request->city,
                    'street' => $request->street,
                    'building' => $request->building,
                    'floor' => $request->floor,
                    'flat' => $request->flat,
                    'address_id' => $request->id,
                ]);
        }

        // Return the address model
        return $this->formResponse("operation successful", null, 200);
    }


    public function updateAddress(Request $request)
    {
        // Validate the request data
        $request->validate([
            'address_id' => 'required',
            'country' => 'required',
            'city' => 'required',
            'street' => 'required',
            'building' => 'nullable',
            'floor' => 'nullable',
            'flat' => 'nullable',
        ]);

        // Retrieve the address from the addresses table
        $address = Address::find($request->get('address_id'));

        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        // Update the address properties from the request data
        $address->country = $request->get('country');
        $address->city = $request->get('city');
        $address->street = $request->get('street');
        $address->building = $request->get('building');
        $address->floor = $request->get('floor');
        $address->flat = $request->get('flat');

        // Save the updated address
        $address->save();

        // Return the updated address
        return response()->json($address);
    }

    public function deleteAddress(Request $request)
    {
        // Retrieve the address from the addresses table
        $address = Address::find($request->address_id);

        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        // Delete the address
        $address->delete();

        // Return a success response
        return response()->json(['message' => 'Address deleted successfully']);
    }
}

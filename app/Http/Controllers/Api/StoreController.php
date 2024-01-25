<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\CartItemsService;
use App\Models\HomeBanner;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Service;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // public function show(Request $request)
    // {
    //     try {
    //         // Validate the request parameters
    //         $validator = Validator::make($request->all(), [
    //             'id' => 'required|integer'
    //         ]);

    //         // Return error response if validation fails
    //         if ($validator->fails()) {
    //             $error = $this->failedValidation($validator);
    //             return $this->formResponse($error, null, 400);
    //         }

    //         $store_id = $request->id;
    //         $cart_id  = $request->cart_id;
    //         $user = $request->user(); // Get the logged-in user

    //         // Retrieve store's products
    //         $products = Product::join('stores_services', 'products.id', '=', 'stores_services.product_id')
    //             ->where('stores_services.store_id', $store_id)
    //             ->select('products.id', 'products.name_en', 'products.name_ar', 'products.image')
    //             ->distinct()
    //             ->get();


    //         // Retrieve images for each product
    //         foreach ($products as $product) {
    //             $product->image = $product->buildImage($product->image);

    //             $services = Service::join('stores_services', 'services.id', '=', 'stores_services.service_id')
    //                 ->where('stores_services.store_id', $store_id)
    //                 ->where('stores_services.product_id', $product->id)
    //                 ->select('services.id', 'services.name_en', 'services.name_ar', 'stores_services.price')
    //                 ->get();
    //             $product->services = $services;
    //         }



    //         // Return the response
    //         return $this->formResponse('Success', [
    //             'products' => $products
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Handle exception
    //         return $this->formResponse('An error occurred while processing your request', $e->getMessage(), 500);
    //     }
    // }

    public function show(Request $request)
    {
        // try {
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'cart_id' => 'required|integer'
        ]);

        // Return error response if validation fails
        if ($validator->fails()) {
            $error = $this->failedValidation($validator);
            return $this->formResponse($error, null, 400);
        }

        $store_id = $request->id;
        $cart_id  = $request->cart_id;
        $user = $request->user(); // Get the logged-in user

        // Retrieve store's products
        $products = Product::join('stores_services', 'products.id', '=', 'stores_services.product_id')
            ->where('stores_services.store_id', $store_id)
            ->select('products.id', 'products.name_en', 'products.name_ar', 'products.image')
            ->distinct()
            ->get();

        // Retrieve images for each product
        foreach ($products as $product) {
            $product->image = $product->buildImage($product->image);

            $services = Service::join('stores_services', 'services.id', '=', 'stores_services.service_id')
                ->where('stores_services.store_id', $store_id)
                ->where('stores_services.product_id', $product->id)
                ->select('services.id', 'services.name_en', 'services.name_ar', 'stores_services.price')
                ->get();
            $product->services = $services;


            $selectedItems = CartItem::where('store_id', $store_id)
                ->where('cart_id', $cart_id)
                ->with('services')
                ->get();
            $selectedServices = [];

            if ($selectedItems) {
                $selectedServices = collect($selectedItems)->flatMap(function ($item) {
                    return $item['services']->pluck('service_id');
                })->unique();
            }

            foreach ($products as $product) {
                if ($product->services) {
                    foreach ($product->services as $service) {
                        $service->isSelected = in_array($service->id, $selectedServices->toArray()) ? 1 : 0;
                    }
                }
            }
        }

        // Return the response
        return $this->formResponse('Success', [
            'products' => $products
        ], 200);
        // } catch (\Exception $e) {
        //     // Handle exception
        //     return $this->formResponse('Operation failed', null, 400);
        // }
    }






    public function storeRating(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'target_id' => 'required|integer',
                'rate_value' => 'required|integer|between:1,5',
            ]);

            $targetUser = User::find($validatedData['target_id']);
            $currentUser = Auth::user();

            if (!$targetUser) {
                return $this->formResponse('Invalid target user ID', null, 400);
            }

            // Check if the target user is the same as the current user
            if ($targetUser->id === $currentUser->id) {
                return $this->formResponse('You cannot rate yourself', null, 400);
            }

            // Check if the current user has already rated the target user
            $existingRating = Rating::where('target_id', $targetUser->id)
                ->where('rated_by', $currentUser->id)
                ->first();

            // if ($existingRating) {
            //     return $this->formResponse('You have already rated', null, 400);
            // }

            $rating = new Rating();
            $rating->target_type = $targetUser->user_type;
            $rating->target_id = $targetUser->id;
            $rating->rated_by = $currentUser->id;
            $rating->rate_value = $validatedData['rate_value'];
            $rating->save();

            return $this->formResponse('Rated successfully', null, 200);
        } catch (\Exception $exception) {
            return $this->formResponse("Something went wrong, please try again later", null, 400);
        }
    }



    public function homeScreen(Request $request)
    {
        $date = Carbon::now();

        try {
            $banners = HomeBanner::where('expiration_date', '>', $date)->take(10)->get();


            $services = Service::all();

            // Loop through each service and use the buildImage() function to set the complete image URL
            foreach ($services as $service) {
                $service->image = $service->buildImage();
            }

            $stores = User::select('users.id', 'users.name', 'users.image', 'users.phone', 'users.latitude', 'users.longitude', DB::raw('ROUND(AVG(rate_value), 1) AS average_rate'))
                ->join('ratings', 'users.id', '=', 'ratings.target_id')
                ->where('users.user_type', 'store')
                ->groupBy('users.id', 'users.name', 'users.image', 'users.phone', 'users.latitude', 'users.longitude')
                ->orderByDesc('average_rate')
                ->limit(5)
                ->get();

            // Loop through each store and use the buildImage() function to set the complete image URL
            foreach ($stores as $store) {
                $store->image = $store->buildImage();
            }

            // Loop through each banner and use the buildImage() function to set the complete image URL
            foreach ($banners as $banner) {
                $banner->image = $banner->buildImage();
            }

            return $this->formResponse('Data retrieved successfully', [
                'banners' => $banners,
                'services' => $services,
                'top_rated_stores' => $stores
            ], 200);
        } catch (\Exception $e) {
            return $this->formResponse('Failed to retrieve data', null, 500);
        }
    }


    public function getStores(Request $request)
    {
        try {

            // Get all stores with user_type 'store'
            $stores = User::select(['id', 'name', 'phone', 'image', 'latitude', 'longitude'])
                ->with(['ratings' => function ($query) {
                    $query->selectRaw('target_id, ROUND(AVG(rate_value), 1) AS average_rate')
                        ->groupBy('target_id');
                }])
                ->where('user_type', 'store')
                ->get();

            // Calculate the distance of each store from the user's location
            foreach ($stores as $store) {
                $store->image = $store->buildImage();
                $store->average_rate = $store->ratings->first() ? $store->ratings->first()->average_rate : null;
                unset($store->ratings);
            }

            // Sort the stores by average rating and distance
            $stores = $stores->sortByDesc('average_rate')->values()->all();

            return $this->formResponse('Data retrieved successfully', [
                'stores' => $stores
            ], 200);
        } catch (\Exception $e) {
            return $this->formResponse('Failed to retrieve data', null, 500);
        }
    }


    // public function SearchStores(Request $request)
    // {
    //     try {
    //         $perPage = $request->input('perPage', 10);
    //         $query = User::where('user_type', 'store');

    //         $nameFilter = $request->input('search');
    //         if ($nameFilter) {
    //             $query->where(function ($q) use ($nameFilter) {
    //                 $q->where('name', 'like', "%$nameFilter%")
    //                     ->orWhere('name_ar', 'like', "%$nameFilter%");
    //             });
    //         }

    //         $stores = $query->orderBy('latitude', 'ASC')->orderBy('longitude', 'ASC')->paginate($perPage);

    //         return $this->formResponse('Data retrieved successfully', [
    //             'stores' => $stores
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return $this->formResponse('Failed to retrieve data', null, 500);
    //     }
    // }

    public function SearchStores(Request $request)
    {
        try {
            $perPage = $request->input('perPage', 10);
            $query = User::where('user_type', 'store');

            $nameFilter = $request->input('search');
            if ($nameFilter) {
                $query->where(function ($q) use ($nameFilter) {
                    $q->where('name', 'like', "%$nameFilter%")
                        ->orWhere('name_ar', 'like', "%$nameFilter%");
                });
            }

            // Get the latitude and longitude from the request
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            if ($latitude && $longitude) {
                // Calculate the distance between the user's location and stores using Haversine formula
                $query->selectRaw("
                users.*,
                (6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude))))
                AS distance
            ");

                // Order the stores by distance
                $query->orderBy('distance', 'ASC');
            } else {
                // Order the stores by latitude and longitude if no user location is provided
                $query->orderBy('latitude', 'ASC')->orderBy('longitude', 'ASC');
            }

            // Join the ratings table and calculate the average rating for each store
            $query->with(['ratings' => function ($query) {
                $query->selectRaw('target_id, AVG(rate_value) as avgRating')
                    ->where('target_type', 'store')
                    ->groupBy('target_id');
            }]);

            $stores = $query->paginate($perPage);

            // Modify the stores data to include avgRating as a single value
            $stores->getCollection()->transform(function ($store) {
                $store->avgRating = $store->ratings->isEmpty() ? "0" : number_format($store->ratings->first()->avgRating, 1);
                unset($store->ratings);
                return $store;
            });

            return $this->formResponse('Data retrieved successfully', [
                'stores' => $stores
            ], 200);
        } catch (\Exception $e) {
            return $this->formResponse('Failed to retrieve data', null, 500);
        }
    }









    // public function storeFilters(Request $request)
    // {
    //     $perPage = $request->input('perPage', 10);
    //     $query = User::where('user_type', 'store');

    //     $filters = $request->input('filters', []);

    //     // Apply each filter
    //     foreach ($filters as $filterName => $filterValue) {
    //         switch ($filterName) {
    //             case 'name':
    //                 $query->where(function ($q) use ($filterValue) {
    //                     $q->where('name', 'like', "%$filterValue%")
    //                         ->orWhere('name_ar', 'like', "%$filterValue%");
    //                 });
    //                 break;
    //                 // case 'serviceName':
    //                 //     $query->whereHas('stores_services', function ($q) use ($filterValue) {
    //                 //         $q->where(function ($q) use ($filterValue) {
    //                 //             $q->where('name_en', 'like', "%$filterValue%")
    //                 //                 ->orWhere('name_ar', 'like', "%$filterValue%");
    //                 //         });
    //                 //     });
    //                 //     break;
    //                 // case 'productName':
    //                 //     $query->whereHas('services', function ($q) use ($filterValue) {
    //                 //         $q->whereHas('product', function ($q) use ($filterValue) {
    //                 //             $q->where(function ($q) use ($filterValue) {
    //                 //                 $q->where('name_en', 'like', "%$filterValue%")
    //                 //                     ->orWhere('name_ar', 'like', "%$filterValue%");
    //                 //             });
    //                 //         });
    //                 //     });
    //                 //     break;
    //             case 'latitude':
    //                 $query->selectRaw('( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$filterValue, $request->input('longitude'), $filterValue])
    //                     ->havingRaw("distance <= ?", [$request->input('radius', 10)])
    //                     ->orderByRaw("distance ASC");
    //                 break;
    //             case 'longitude':
    //                 // This filter is already handled by the latitude filter
    //                 break;
    //             case 'radius':
    //                 // This filter is already handled by the latitude filter
    //                 break;
    //             default:
    //                 // Unknown filter
    //                 break;
    //         }
    //     }

    //     $stores = $query->paginate($perPage);
    //     return response()->json($stores);
    // }

    public function storeFilters(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = User::where('user_type', 'store');

        $filters = $request->input('filters', []);

        // Apply each filter
        foreach ($filters as $filterName => $filterValue) {
            switch ($filterName) {
                case 'name':
                    $query->where(function ($q) use ($filterValue) {
                        $q->where('name', 'like', "%$filterValue%")
                            ->orWhere('name_ar', 'like', "%$filterValue%");
                    });
                    break;
                default:
                    // Unknown filter
                    break;
            }
        }

        // Apply orderBy location
        $query->orderBy('latitude', 'ASC')->orderBy('longitude', 'ASC');

        $stores = $query->paginate($perPage);

        // If no filters are applied, return all data
        if (empty($filters)) {
            $stores = User::where('user_type', 'store')
                ->orderBy('latitude', 'ASC')
                ->orderBy('longitude', 'ASC')
                ->paginate($perPage);
        }

        return response()->json($stores);
    }
}

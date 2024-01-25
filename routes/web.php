<?php

use App\Http\Controllers\Dashboard\OrdersController;
use App\Http\Controllers\Dashboard\OrderItemsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ServicesController;
use App\Http\Controllers\Dashboard\StoresController;
use App\Http\Controllers\Dashboard\StoresServicesController;
use App\Http\Controllers\Dashboard\HomeBannersController;
use App\Http\Controllers\Dashboard\AppSettingsController;
use App\Http\Controllers\Dashboard\CustomersController;
use App\Http\Controllers\Dashboard\StatisticsController;
use App\Http\Controllers\Dashboard\StoreProfileController;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider and all of them will
  | be assigned to the "web" middleware group. Make something great!
  |
 */

Route::get('/', function () {
    // return view('welcome');
    return redirect('dashboard/login');
});


Route::post('backend/login', 'App\Http\Controllers\Dashboard\AdminController@login');
Route::post('backend/store/login', 'App\Http\Controllers\Dashboard\AdminController@storeLogin');


Route::get('dashboard/login', function () {
    if (Auth::check()) {
        return redirect('dashboard/products');
    } else
        return view('dashboard.login');
})->name('login');


Route::get('dashboard/store/login', function () {
    $user = \App\Models\User::where('user_type', 'store')->get();
    foreach ($user as $u) {
        if (Auth::check($u)) {
            return redirect('dashboard/store-services' . '/' . $u->id);
        } else
            return view('dashboard.store-login');
    }
});



Route::group(
    ['middleware' => ['auth', 'admin-access:admin'], 'prefix' => 'dashboard/'],
    function () {
        Route::post('backend/reset-password', 'App\Http\Controllers\Dashboard\ResetPasswordController@resetPassword');
    }
);

Route::group(
    ['middleware' => ['auth', 'user-access:store'], 'prefix' => 'dashboard/store/'],
    function () {
        Route::post('backend/reset-password/{id}', 'App\Http\Controllers\Dashboard\ResetPasswordController@storeResetPassword');
    }
);

Route::group(
    ['middleware' => 'auth', 'prefix' => 'dashboard/'],
    function () {
        Route::get('logout', 'App\Http\Controllers\Dashboard\AdminController@logout')->name('logout');    
    }
);



Route::middleware(['auth', 'admin-access:admin'])->group(
    function () {

        Route::get('home', function () {
            return redirect('dashboard/products');
        });
        Route::get('dashboard', function () {
            return redirect('dashboard/products');
        });
        Route::get('dashboard/home', function () {
            return view('dashboard.products');
        });
        Route::get('dashboard/products', function () {
            return view('dashboard.products');
        });
        Route::get('dashboard/services', function () {
            return view('dashboard.services');
        });
        Route::get('dashboard/stores', function () {
            return view('dashboard.stores');
        });
        Route::get('dashboard/stores-services', function () {
            return view('dashboard.stores-services');
        });
        Route::get('dashboard/orders', function () {
            return view('dashboard.orders');
        });
        Route::get('dashboard/single-order/{id}', function () {
            return view('dashboard.single-order');
        });
        Route::get('dashboard/home-banners', function () {
            return view('dashboard.home-banners');
        });
        Route::get('dashboard/app-settings', function () {
            return view('dashboard.app-settings');
        });
        Route::get('dashboard/customers', function () {
            return view('dashboard.customers');
        });
        Route::get('dashboard/statistics', function () {
            return view('dashboard.statistics');
        });

        Route::get('dashboard/reset-password', function () {
            return view('dashboard.reset-password');
        });


    }
);



Route::middleware(['auth', 'user-access:store'])->group(function () {

    Route::get('dashboard/store-services/{id}', function () {
        return view('dashboard.single-store-services');
    });

    Route::get('dashboard/store-orders/{id}', function () {
        return view('dashboard.single-store-orders');
    });

    Route::get('dashboard/single-store-order-items/{id}/{order_id}', function () {
        return view('dashboard.single-store-order-items');
    });

    Route::get('dashboard/store-profile/{id}', function () {
        return view('dashboard.single-store-profile');
    });


    Route::get('dashboard/store/reset-password/{id}', function () {
        return view('dashboard.store-reset-password');
    });

});



Route::group(
    ['middleware' => ['auth', 'admin-access:admin'], 'prefix' => 'backend-crud/v1/'],
    function () {

        Route::get('products/fetchall', [ProductsController::class, 'fetchAll']);
        Route::post('products/store', [ProductsController::class, 'store']);
        Route::delete('products/delete', [ProductsController::class, 'delete']);
        Route::post('products/update', [ProductsController::class, 'update']);

        Route::get('stores/fetchall', [StoresController::class, 'fetchAll']);
        Route::post('stores/store', [StoresController::class, 'store']);
        Route::delete('stores/delete', [StoresController::class, 'delete']);
        Route::post('stores/update', [StoresController::class, 'update']);

        Route::get('services/fetchall', [ServicesController::class, 'fetchAll']);
        Route::post('services/store', [ServicesController::class, 'store']);
        Route::delete('services/delete', [ServicesController::class, 'delete']);
        Route::post('services/update', [ServicesController::class, 'update']);

        Route::get('stores-services/fetchall', [StoresServicesController::class, 'fetchAll']);
        Route::get('stores-services/fetchProductImage/{select}', [StoresServicesController::class, 'fetchImage']);
        Route::post('stores-services/store', [StoresServicesController::class, 'store']);
        Route::delete('stores-services/delete', [StoresServicesController::class, 'delete']);
        Route::post('stores-services/update', [StoresServicesController::class, 'update']);

        Route::get('home-banners/fetchall', [HomeBannersController::class, 'fetchAll']);
        Route::post('home-banners/store', [HomeBannersController::class, 'store']);
        Route::delete('home-banners/delete', [HomeBannersController::class, 'delete']);
        Route::post('home-banners/update', [HomeBannersController::class, 'update']);

        Route::get('orders/fetchall', [OrdersController::class, 'fetchAll']);
        Route::delete('orders/delete', [OrdersController::class, 'delete']);
        Route::post('orders/update', [OrdersController::class, 'update']);

        Route::get('single-order/fetchall/{id}', [OrderItemsController::class, 'fetchAll']);
        Route::delete('single-order/delete', [OrderItemsController::class, 'delete']);
        Route::post('single-order/update', [OrderItemsController::class, 'update']);

        Route::get('app-settings/fetchall', [AppSettingsController::class, 'fetchAll']);
        Route::post('app-settings/update', [AppSettingsController::class, 'update']);

        Route::get('customers/fetchall', [CustomersController::class, 'fetchAll']);

        Route::post('statistics/request', [StatisticsController::class, 'fetchByDate']);
        Route::get('statistics/fetchall', [StatisticsController::class, 'fetchAll']);

    }
);


Route::group(
    ['middleware' => ['auth', 'user-access:store'], 'prefix' => 'backend-crud/v1/'],
    function () {

        Route::get('store-services/fetchall/{id}', [StoresServicesController::class, 'fetchByStore']);
        Route::get('store-services/fetchProductImage/{select}/{id}', [StoresServicesController::class, 'fetchImage']);
        Route::post('store-services/store/{id}', [StoresServicesController::class, 'store']);
        Route::delete('store-services/delete/{id}', [StoresServicesController::class, 'delete']);
        Route::post('store-services/update/{id}', [StoresServicesController::class, 'update']);

        Route::get('store-orders/fetchall/{id}', [OrdersController::class, 'fetchByStore']);
        Route::delete('store-orders/delete/{id}', [OrdersController::class, 'delete']);
        Route::post('store-orders/update/{id}', [OrdersController::class, 'update']);

        Route::get('store-order-items/fetchall/{id}/{order_id}', [OrderItemsController::class, 'fetchStoreOrderItems']);
        Route::delete('store-order-items/delete/{id}/{order_id}', [OrderItemsController::class, 'delete']);
        Route::post('store-order-items/update/{id}/{order_id}', [OrderItemsController::class, 'update']);

        Route::post('store-profile/update/{id}', [StoreProfileController::class, 'update']);
        Route::get('store-profile/fetchall/{id}', [StoreProfileController::class, 'fetchAll']);

    }
);

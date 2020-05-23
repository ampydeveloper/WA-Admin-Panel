<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Auth Routes
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function () {
    //Auth free routes
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('confirm-email/{decode_code}', 'AuthController@confirmEmail');

    //Auth full routes
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');

        Route::get('user', 'AuthController@user');

        Route::group(['prefix' => 'admin'], function () {
            //update admin profile
            Route::post('edit-profile', 'AuthController@editProfile');
            Route::post('change-password', 'AuthController@changePassword');

            //manager
            Route::post('create-manager', 'ManagerController@createManager');
            Route::post('update-manager/{manager_id}', 'ManagerController@updateManager');
            Route::get('delete-manager/{manager_id}', 'ManagerController@deleteManager');
            Route::get('get-manager/{manager_id}', 'ManagerController@getManager');
            Route::get('list-manager', 'ManagerController@listManager');

            //services
            Route::post('create-service', 'ServicesController@createService');
            Route::post('edit-service/{service_id}', 'ServicesController@editService');
            Route::post('edit-service-time-slot/{time_slot_id}', 'ServicesController@editTimeSlot');
            Route::get('list-services', 'ServicesController@listServices');
            Route::get('get-service/{service_id}', 'ServicesController@getService');
            Route::delete('delete-service/{service_id}', 'ServicesController@deleteService');

            //driver
            Route::post('create-driver', 'DriverController@createDriver');
            Route::post('edit-driver/{driver_id}', 'DriverController@editDriver');
            Route::get('list-drivers', 'DriverController@listDrivers');
            Route::get('get-driver/{driver_id}', 'DriverController@getDriver');
            Route::delete('delete-driver/{driver_id}', 'DriverController@deleteDriver');

            //truck
            Route::post('create-vehicle', 'VehicleController@createVehicle');
            Route::post('edit-vehicle/{vehicle_id}', 'VehicleController@editVehicle');
            Route::get('list-vehicle', 'VehicleController@listVehicle');
            Route::get('list-skidsteer', 'VehicleController@listSkidsteer');
            Route::get('get-vehicle/{vehicle_id}', 'VehicleController@getVehicle');
            Route::delete('delete-vehicle/{vehicle_id}', 'VehicleController@deleteVehicle');
        });

        //upload image
        Route::post('uploadImage', 'ImageController@uploadImage');
    });
});

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
//    dd('here');
    Route::post('signup', 'AuthController@signup');
    Route::post('social-signup', 'AuthController@SocialSignup');
    Route::post('login', 'AuthController@login');
    Route::post('forgot-password', 'AuthController@forgotPassword');
    Route::post('change-password', 'AuthController@changePassword');
    Route::post('recover-password', 'AuthController@recoverPassword');
    Route::get('confirm-update-email/{email}/{id}', 'AuthController@confirmUpdateEmail');
    
    Route::get('test-task', 'CronController@assignTecs');
    
    Route::get('set-task', 'SetTaskController@setTaskCronjob');
    Route::get('finish-job-from-farm/{id}', 'SetTaskController@finishJobFromFarm');

    //Auth full routes
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');

        Route::get('user-details', 'AuthController@user');

        Route::group(['prefix' => 'admin'], function () {
            //update admin profile
            Route::post('edit-admin-profile', 'AuthController@editAdminProfile');
            Route::post('change-password', 'AuthController@changePassword');
            Route::post('create-admin', 'ManagerController@createAdmin');

            //manager
//            dd('here');
//            Route::post('create-manager1', function() {
//                    dd('here1');
//            });
            Route::post('create-manager-admin', 'ManagerController@createManager');
            Route::post('update-manager/{manager_id}', 'ManagerController@updateManager');
            Route::get('get-manager/{manager_id}', 'ManagerController@getManager');
            Route::delete('delete-manager/{manager_id}', 'ManagerController@deleteManager');
            Route::get('list-manager', 'ManagerController@listManager');
            //admin
            Route::get('list-admin', 'ManagerController@listAdmin');
            Route::get('get-admin/{admin_id}', 'ManagerController@getAdmin');

            //services
            Route::post('create-service', 'ServicesController@createService');
            Route::post('edit-service', 'ServicesController@editService');
//            Route::post('edit-service-time-slot/{time_slot_id}', 'ServicesController@editTimeSlot');
            Route::get('list-services', 'ServicesController@listServices');
            Route::get('get-service/{service_id}', 'ServicesController@getService');
            Route::delete('delete-service/{service_id}', 'ServicesController@deleteService');
            Route::post('get-timeslots', 'ServicesController@getTimeSlots');

            //customer
            Route::get('list-customer', 'CustomerController@listCustomer');
            Route::post('create-customer', 'CustomerController@createCustomer');
            Route::post('create-farm', 'CustomerController@createFarm');
            Route::post('create-manager', 'CustomerController@createCustomerManager');
            Route::get('get-customer/{customer_id}', 'CustomerController@getCustomer');
            Route::get('get-farm/{customer_id}', 'CustomerController@getFarms');
            Route::get('get-farm-and-manager/{customer_id}', 'CustomerController@getCustomerManager');
            Route::get('get-farm-manager/{farm_id}', 'CustomerController@getFarmManager');
            Route::get('card-list/{customer_id}', 'CustomerController@getAllCard');
            Route::get('record-list/{customer_id}', 'CustomerController@getAllRecords');
            Route::post('update-customer', 'CustomerController@updateCustomer');
            Route::post('update-farm', 'CustomerController@updateFarm');
            Route::post('update-manager', 'CustomerController@updateManager');
            //Route::delete('delete-driver/{driver_id}', 'CustomerController@deleteDriver');

            //CompanyController
            Route::get('list-hauler', 'CompanyController@listHauler');
            Route::post('create-hauler', 'CompanyController@createHauler');
            Route::get('get-hauler/{customer_id}', 'CompanyController@getHauler');
            Route::post('update-hauler/{customer_id}', 'CompanyController@updateHauler');
            Route::delete('delete-hauler/{customer_id}', 'CompanyController@deleteHauler');

            //jobs
            Route::post('job-list', 'JobsController@getAllJob');
            Route::post('job-filter', 'JobsController@jobFilter');
            Route::get('job-customer', 'JobsController@getCustomers');
            Route::get('job-farms/{customer_id}/{manager_id}', 'JobsController@getJobFrams');
            Route::get('get-service-slots/{service_id}', 'JobsController@getServiceSlots');
            Route::post('create-job', 'JobsController@createJob');
            Route::get('single-job/{job_id}', 'JobsController@getSingleJob');
            Route::post('update-booked-job', 'JobsController@updateBookedJob');
            Route::post('cancel-booked-job', 'JobsController@cancelJob');
            
//            Route::get('fetch-job-details/{unique_job_id}', 'JobsController@fetchJobDetails');
            // Route::get('assigned-job-list', 'JobsController@getAssignedJob');
            // Route::get('complete-job-list', 'JobsController@getCompleteJob');
            //  Route::get('open-job-list', 'JobsController@getOpenJob');
            // Route::get('repeating-job-list', 'JobsController@getRepeatingJob');
            //Route::get('unpaid-job-list', 'JobsController@getUnpaidJob');
//            Route::get('dispatch-job-list', 'JobsController@getDispatchJob');

            //driver
            Route::get('list-drivers', 'DriverController@listDrivers');
            Route::post('create-driver', 'DriverController@createDriver');
            Route::post('edit-driver/{driver_id}', 'DriverController@editDriver');
            Route::get('get-driver/{driver_id}', 'DriverController@getDriver');
            Route::delete('delete-driver/{driver_id}', 'DriverController@deleteDriver');

            //truck
            Route::get('list-vehicle', 'VehicleController@listVehicle');
            Route::post('create-vehicle', 'VehicleController@createVehicle');
            Route::post('edit-vehicle/{vehicle_id}', 'VehicleController@editVehicle');
            Route::get('get-vehicle/{vehicle_id}', 'VehicleController@getVehicle');
            Route::delete('delete-vehicle/{vehicle_id}', 'VehicleController@deleteVehicle');
            
            Route::post('create-vehicleinsurance', 'VehicleController@createVehicleInsurance');
            Route::post('edit-vehicle-insurance/{vehicle_insurance_id}', 'VehicleController@editVehicleInsurance');
            Route::get('get-insurance-details/{insurance_id}', 'VehicleController@getInsuranceDetails');
            Route::get('get-last-insurance/{vehicle_id}', 'VehicleController@getLastInsurance');
            Route::get('get-vehicleinsurance/{vehicle_id}', 'VehicleController@getVehicleInsurance');
            Route::delete('delete-insurance-details/{insurance_id}', 'VehicleController@deleteInsuranceDetails');
            
            Route::post('create-vehicleservice', 'VehicleController@createVehicleService');
            Route::post('edit-vehicle-service/{vehicle_service_id}', 'VehicleController@editVehicleService');
            Route::get('get-vehicleservice/{vehicle_id}', 'VehicleController@getVehicleService');
            Route::get('get-service-details/{service_id}', 'VehicleController@getServiceDetails');
            Route::delete('delete-service-details/{service_id}', 'VehicleController@deleteServiceDetails');
            
            Route::get('list-skidsteer', 'VehicleController@listSkidsteer');
//            Route::post('save-service-details/{service_id}', 'VehicleController@saveServiceDetails');
//            Route::post('save-insurance-details/{insurance_id}', 'VehicleController@saveInsuranceDetails');
            
            //accounting
            Route::get('/job-invoices', 'AccountingController@getAllJobInvoices');
            Route::get('/job-payment', 'AccountingController@getAllJobPayment');
            Route::get('/job-salary', 'AccountingController@getAllJobSalary');
            Route::get('/job-salary-details/{driver_id}', 'AccountingController@getSingleJobSalary');

            //message
            Route::post('chat-send', 'MessageController@send');

            //stripe
            Route::post('stripe-charge', 'PaymentController@stripeCharge');
        });
        
        Route::group(['prefix' => 'user'], function () {
//    dd('api');
            Route::get('dashboard', 'User\CustomerController@dashboard');
            
            Route::post('edit-profile', 'User\CustomerController@editProfile');
            
            Route::post('create-farm', 'User\CustomerController@createFarm');
            Route::post('update-farm', 'User\CustomerController@updateFarm');
            Route::get('get-farms', 'User\CustomerController@getFarms');
            Route::get('get-farm/{farm_id}', 'User\CustomerController@getSingleFarm');
            Route::delete('delete-farm/{farm_id}', 'User\CustomerController@deleteFarm');
//            dd('user');
            Route::post('create-manager', 'User\CustomerController@createManager');
            Route::post('update-manager', 'User\CustomerController@updateManager');
            Route::get('managers-list', 'User\CustomerController@managerLists');
            Route::get('get-manager/{manager_id}', 'User\CustomerController@getSingleManager');
            Route::delete('delete-manager/{manager_id}', 'User\CustomerController@deleteManager');
            
            Route::get('services-list', 'User\ServicesController@listServices');
            Route::post('book-service', 'User\ServicesController@bookService');
            Route::get('show-customer-services', 'User\ServicesController@showCustomerServices');
            Route::post('update-booked-service', 'User\ServicesController@updateBookedService');
            Route::post('cancel-booked-job', 'User\ServicesController@cancelJob');
            Route::post('job-filter', 'User\ServicesController@jobFilter');
            
            Route::get('card-list', 'User\CustomerController@getAllCard');
            Route::get('record-list', 'User\CustomerController@getAllRecords');
            Route::get('primary-card', 'User\CustomerController@getPrimaryCard');
            Route::post('add-card', 'User\CustomerController@addCard');
            Route::post('change-primary-card', 'User\CustomerController@changePrimaryCard');

        });
        
        Route::group(['prefix' => 'driver'], function () {
            Route::get('dashboard', 'Driver\DriverController@dashboard');
            Route::post('edit-profile', 'Driver\DriverController@editProfile');
            Route::get('earnings', 'Driver\DriverController@earnings');
            Route::get('routes', 'Driver\DriverController@routes');
            Route::get('start-route', 'Driver\DriverController@startRoute');
            Route::get('start-job', 'Driver\DriverController@startJob');
            Route::get('end-route', 'Driver\DriverController@endRoute');
            Route::get('end-job', 'Driver\DriverController@endJob');
            Route::post('job-filter', 'Driver\DriverController@jobFilter');
        });
        

        //upload image
        Route::post('uploadImage', 'ImageController@uploadImage');
        Route::delete('deleteImage', 'ImageController@deleteImage');
    });
});


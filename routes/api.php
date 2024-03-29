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
//    Route::post('signup', 'AuthController@signup');
//    Route::post('social-signup', 'AuthController@SocialSignup');
    Route::post('login', 'AuthController@login');
    Route::post('send-otp', 'AuthController@sendOtp');
    Route::post('check-otp', 'AuthController@checkOtp');
    Route::post('forget-password-mobile', 'AuthController@forgotPasswordMobile');
    Route::post('forgot-password', 'AuthController@forgotPassword');

    Route::post('recover-password', 'AuthController@recoverPassword');
    Route::get('confirm-update-email/{email}/{id}', 'AuthController@confirmUpdateEmail');

    Route::get('test-task', 'CronController@assignTecs');

    Route::get('set-task', 'SetTaskController@setTaskCronjob');
    Route::get('finish-job-from-farm/{id}', 'SetTaskController@finishJobFromFarm');

    Route::post('start-time', 'TrackDriverTimeController@startJobTimer');
    Route::post('stop-time', 'TrackDriverTimeController@stopJobTimer');

    Route::post('job-chat', 'ChatController@jobChat');
    Route::get('chat-members/{job_id}', 'ChatController@chatMembers');
    Route::get('chat-members-and/{job_id}', 'ChatController@chatMembersAnd');

    //Auth full routes
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('change-password', 'AuthController@changePassword');
        Route::get('logout', 'AuthController@logout');

        Route::get('user-details', 'AuthController@user');

        //upload image
        Route::post('uploadImage', 'ImageController@uploadImage');
        Route::post('uploadImageFile', 'ImageController@uploadImageFile');
        Route::delete('deleteImage', 'ImageController@deleteImage');

        Route::group(['prefix' => 'admin'], function () {
            Route::get('dashboard', 'ManagerController@dashboard');
            Route::post('dashboard-filters', 'ManagerController@dashboardFilters');
            Route::post('edit-profile', 'ManagerController@editProfile');

            //Dispatch
            Route::get('dispatches/{dt?}', 'JobsController@dispatches');
            Route::post('job-by-map', 'JobsController@jobByMap');
            Route::patch('update-driver-vehicle/{job_id}/{type}/{id}', 'JobsController@updateDriverVehicle');

            //update admin profile
            Route::post('change-password', 'AuthController@changePassword');
            Route::post('create-admin', 'ManagerController@createAdmin');
            Route::post('edit-admin-profile', 'ManagerController@editAdminProfile');
            //admin
            Route::get('list-admin', 'ManagerController@listAdmin');
            Route::get('get-admin/{admin_id}', 'ManagerController@getAdmin');
            Route::post('list-admin-mobile', 'ManagerController@listAdminMobile');

            Route::post('create-manager-admin', 'ManagerController@createManager');
            Route::post('update-manager-admin', 'ManagerController@updateManager');
            Route::get('get-manager/{manager_id}', 'ManagerController@getManager');
            Route::delete('delete-manager/{manager_id}', 'ManagerController@deleteManager');
            Route::get('list-manager', 'ManagerController@listManager');
            Route::post('list-manager-mobile', 'ManagerController@listManagerMobile');

            //services
            Route::post('create-service', 'ServicesController@createService');
            Route::post('edit-service', 'ServicesController@editService');
//            Route::post('edit-service-time-slot/{time_slot_id}', 'ServicesController@editTimeSlot');
            Route::get('list-services/{role_id?}', 'ServicesController@listServices');
            Route::post('list-services-mobile', 'ServicesController@listServicesMobile');
            Route::get('get-service/{service_id}', 'ServicesController@getService');
            Route::delete('delete-service/{service_id}', 'ServicesController@deleteService');
            Route::post('get-timeslots', 'ServicesController@getTimeSlots');

            //customer
            Route::get('list-customer', 'CustomerController@listCustomer');
            Route::post('list-customer-mobile', 'CustomerController@listCustomerMobile');
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
            Route::post('update-farm-and-managers', 'CustomerController@updateFarmWManagers');
            Route::post('update-customer-payment-mode', 'CustomerController@updateCustomerPaymentMode');
            Route::get('resetCustomerPassword/{cid}', 'CustomerController@resetCustomerPassword');

            //CompanyController
            Route::get('list-hauler', 'CompanyController@listHauler');
            Route::post('list-hauler-mobile', 'CompanyController@listHaulerMobile');
            Route::post('create-hauler', 'CompanyController@createHauler');
            Route::get('get-hauler/{customer_id}', 'CompanyController@getHauler');
            Route::post('update-hauler', 'CompanyController@updateHauler');
            Route::delete('delete-hauler/{customer_id}', 'CompanyController@deleteHauler');


            Route::get('list-hauler-driver/{hauler_id}', 'CompanyController@listHaulerDriver');
            Route::post('list-hauler-driver-mobile', 'CompanyController@listHaulerMobileDriver');
            Route::post('create-hauler-driver', 'CompanyController@createHaulerDriver');
            Route::get('get-hauler-driver/{hauler_driver_id}', 'CompanyController@getHaulerDriver');
            Route::post('edit-hauler-driver', 'CompanyController@editHaulerDriver');
            Route::delete('delete-hauler-driver/{hauler_driver_id}', 'CompanyController@deleteHaulerDriver');

            //jobs
            Route::get('job-list', 'JobsController@getAllJob');
            Route::post('job-list-mobile-all-jobs', 'JobsController@getAllJobMobile');
            Route::post('job-list-mobile-repeating-jobs', 'JobsController@getRepeatingJobMobile');
            Route::post('job-filter', 'JobsController@jobFilter');
            Route::post('create-job', 'JobsController@createJob');
            Route::get('job-customer', 'JobsController@getCustomers');
            Route::get('job-farms/{customer_id}', 'JobsController@getJobFrams');
            Route::get('job-farms-manager/{farm_id}', 'JobsController@getJobFramManagers');
            Route::get('job-hauler-drivers/{hauler_id}', 'JobsController@getHaulerDrivers');
            Route::get('service-list-customer/{customer_id}', 'JobsController@getServiceForCustomer');
            Route::get('single-job/{job_id}', 'JobsController@getSingleJob');
            Route::post('update-booked-job', 'JobsController@updateBookedJob');
            Route::post('cancel-booked-job', 'JobsController@cancelJob');
            Route::post('job-by-map', 'JobsController@jobByMap');

            //driver
            Route::get('list-drivers', 'DriverController@listDrivers');
            Route::post('list-drivers-mobile', 'DriverController@listDriversMobile');
            Route::post('create-driver', 'DriverController@createDriver');
            Route::post('edit-driver', 'DriverController@editDriver');
            Route::get('get-driver/{driver_id}', 'DriverController@getDriver');
            Route::delete('delete-driver/{driver_id}', 'DriverController@deleteDriver');

            Route::get('invoice', 'EmailTemplateController@invoice');
            Route::get('get-emails', 'EmailTemplateController@getEmails');
            Route::post('save-emails', 'EmailTemplateController@saveEmails');

            Route::get('invoice', 'EmailTemplateController@invoice');
            Route::get('get-emails', 'EmailTemplateController@getEmails');
            Route::post('save-emails', 'EmailTemplateController@saveEmails');

            //truck
            Route::get('list-vehicle', 'VehicleController@listVehicle');
            Route::post('list-vehicle-mobile', 'VehicleController@listVehicleMobile');
            Route::get('list-vehicle-skidsteer', 'VehicleController@listVehicleSkidsteer');
            Route::post('list-vehicle-mobile-skidsteer', 'VehicleController@listVehicleMobileSkidsteer');
            Route::post('create-vehicle', 'VehicleController@createVehicle');
            Route::post('edit-vehicle', 'VehicleController@editVehicle');
            Route::get('get-vehicle/{vehicle_id}', 'VehicleController@getVehicle');
            Route::delete('delete-vehicle/{vehicle_id}', 'VehicleController@deleteVehicle');

            Route::post('create-vehicleinsurance', 'VehicleController@createVehicleInsurance');
            Route::post('edit-vehicle-insurance', 'VehicleController@editVehicleInsurance');
            Route::get('get-insurance-details/{insurance_id}', 'VehicleController@getInsuranceDetails');
            Route::get('get-last-insurance/{vehicle_id}', 'VehicleController@getLastInsurance');
            Route::get('get-vehicleinsurance/{vehicle_id}', 'VehicleController@getVehicleInsurance');
            Route::post('get-vehicle-insurance-mobile/{vehicle_id}', 'VehicleController@getVehicleInsuranceMobile');
            Route::delete('delete-insurance-details/{insurance_id}', 'VehicleController@deleteInsuranceDetails');

            Route::post('create-vehicleservice', 'VehicleController@createVehicleService');
            Route::post('edit-vehicle-service', 'VehicleController@editVehicleService');
            Route::get('get-service-details/{service_id}', 'VehicleController@getServiceDetails');
            Route::get('get-last-service/{vehicle_id}', 'VehicleController@getLastService');
            Route::get('get-vehicleservice/{vehicle_id}', 'VehicleController@getVehicleService');
            Route::post('get-vehicle-service-mobile/{vehicle_id}', 'VehicleController@getVehicleServiceMobile');
            Route::delete('delete-service-details/{service_id}', 'VehicleController@deleteServiceDetails');

            Route::get('list-skidsteer', 'VehicleController@listSkidsteer');
//            Route::post('save-service-details/{service_id}', 'VehicleController@saveServiceDetails');
//            Route::post('save-insurance-details/{insurance_id}', 'VehicleController@saveInsuranceDetails');
            //accounting
            Route::get('job-invoices', 'AccountingController@getAllJobInvoices');
            Route::get('job-payment', 'AccountingController@getAllJobPayment');
            Route::post('get-payment', 'AccountingController@getPayment');
            Route::get('job-salary', 'AccountingController@getAllJobSalary');
            Route::get('pay-salary/{driver_id}', 'AccountingController@paySalary');

            //news
            Route::post('create-news', 'NewsController@createNews');
            Route::get('news-list', 'NewsController@newsList');
            Route::get('single-news/{news_id}', 'NewsController@singleNews');
            Route::post('update-news', 'NewsController@updateNews');
            Route::delete('delete-news/{news_id}', 'NewsController@deleteNews');

            //faq
            Route::get('faq-list', 'NewsController@faqList');
            Route::post('create-faq', 'NewsController@createFaq');
            Route::get('single-faq/{faq_id}', 'NewsController@singleFaq');
            Route::post('update-faq', 'NewsController@updateFaq');
            Route::delete('delete-faq/{faq_id}', 'NewsController@deleteFaq');

            //mechanic
            Route::get('mechanic-list', 'ManagerController@mechanicList');
            Route::post('create-mechanic', 'ManagerController@createMechanic');
            Route::get('single-mechanic/{mechanic_id}', 'ManagerController@singleMechanic');
            Route::post('update-mechanic', 'ManagerController@updateMechanic');
            Route::delete('delete-mechanic/{mechanic_id}', 'ManagerController@deleteMechanic');

            //message
            Route::post('chat-send', 'MessageController@send');

            //stripe
            Route::post('stripe-charge', 'PaymentController@stripeCharge');

            //Report
            Route::post('sales-by-customer', 'ReportController@salesByCustomer');
            Route::post('sales-by-service-tech', 'ReportController@salesByServiceTech');
            Route::post('transactions-by-customer', 'ReportController@transactionsByCustomer');
            Route::post('transactions-by-job', 'ReportController@transactionsByJob');
            Route::post('customer-list', 'ReportController@customerList');
            Route::post('customer-farm-list', 'ReportController@customerFarmList');
            
            Route::post('get-report', 'ReportController@getCustomerReport');
            Route::post('job-activity-report', 'ReportController@jobActivityReport');
        });
        Route::group(['prefix' => 'driver'], function () {
            Route::get('', 'Driver\DriverController@getDriver');
            Route::post('edit-profile', 'Driver\DriverController@editProfile');
            Route::get('delivered-jobs', 'Driver\DriverController@deliveredJobs');
            Route::get('ongoing-jobs', 'Driver\DriverController@ongoingJobs');
            Route::get('job-detail/{job_id}', 'Driver\DriverController@jobDetail');
            Route::post('start-job', 'Driver\DriverController@startJob');
            Route::post('end-job', 'Driver\DriverController@endJob');
            Route::get('earnings', 'Driver\DriverController@earnings');
            Route::post('earnings-filter', 'Driver\DriverController@earningsFilter');
            Route::post('status', 'Driver\DriverController@driverStatus');

            Route::get('dashboard', 'Driver\DriverController@dashboard');
            Route::get('routes', 'Driver\DriverController@routes');
            Route::get('start-route', 'Driver\DriverController@startRoute');
            Route::get('end-route', 'Driver\DriverController@endRoute');
            Route::post('job-filter', 'Driver\DriverController@jobFilter');
        });
    });
});



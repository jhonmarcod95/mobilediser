<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    #dashboard
    Route::group(['middleware' => ['permission:view.dashboard']], function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/getInStore/{date}', 'HomeController@getInStore');
        Route::get('/getInventory/{date}', 'HomeController@getInventory');
        Route::get('/getOfftake/{date}', 'HomeController@getOfftake');
        Route::get('/getSchedule/{date}', 'HomeController@getSchedule');
        Route::get('/getNearExpiry/{date}', 'HomeController@getNearExpiry');
        Route::get('/getRecentlyLogin', 'HomeController@getRecentlyLogin');
    });

    #announcement
    Route::group(['middleware' => ['permission:view.announcements']], function () {
        Route::get('/announcements', 'AnnouncementController@show');
        Route::get('/announcements/edit/{id}', 'AnnouncementController@edit');
    });

    Route::post('/announcement/post', 'AnnouncementController@post')->middleware('permission:create.announcements');
    Route::post('/announcement/add', 'AnnouncementController@add')->middleware('permission:create.announcements');
    Route::post('/announcements/update/{id}', 'AnnouncementController@update')->middleware('permission:edit.announcements');

    #message
    Route::group(['middleware' => ['permission:view.messages']], function () {
        Route::get('/messages', 'MessageController@index');
        Route::get('/messages-all', 'MessageController@indexData');
        Route::get('/message/chat/{id}', 'MessageController@chat');
    });

    Route::post('/message/addChat', 'MessageController@addChat')->middleware('permission:chat.messages');
    Route::post('/message/closeMessage', 'MessageController@closeMessage')->middleware('permission:chat.messages');

    #schedule
    Route::group(['middleware' => ['permission:view.schedules']], function () {
        Route::get('/schedules', 'ScheduleController@index');
        Route::get('/schedules-data', 'ScheduleController@indexData');
        Route::get('/schedules/show/{id}', 'ScheduleController@show');
        Route::get('/schedules/records/{merchandiser_id}/{date}', 'ScheduleController@records');
        Route::get('/schedules/edit/{id}', 'ScheduleController@edit');
    });

    #schedule posts
    Route::post('/schedules/save', 'ScheduleController@save')->middleware('permission:create.schedules');
    Route::post('/schedules/update', 'ScheduleController@update')->middleware('permission:edit.schedules');
    Route::post('/schedules/delete', 'ScheduleController@delete')->middleware('permission:delete.schedules');
    Route::post('/schedules/upload', 'ScheduleController@upload')->middleware('permission:upload.schedules');

    #users
    Route::group(['middleware' => ['permission:view.users']], function () {
        Route::get('/users', 'UserController@index');
        Route::get('/users-all', 'UserController@indexData');
    });

    #users posts
    Route::post('/users/save', 'UserController@save')->middleware('permission:create.users');
    Route::post('/users/update', 'UserController@update')->middleware('permission:edit.users');

    #agencies
    Route::group(['middleware' => ['permission:view.agencies']], function () {
        Route::get('/agencies', 'AgencyController@index');
        Route::get('/agency-all', 'AgencyController@indexData');
    });

    Route::post('/agencies/save', 'AgencyController@save')->middleware('permission:create.agencies');
    Route::post('/agencies/update', 'AgencyController@update')->middleware('permission:edit.agencies');

    #customers
    Route::group(['middleware' => ['permission:view.customers']], function () {
        Route::get('/customers', 'CustomerController@index');
        Route::get('/customer-all', 'CustomerController@indexData');
    });

    #customer posts
    Route::post('/customers/save', 'CustomerController@save')->middleware('permission:create.customers');
    Route::post('/customers/update', 'CustomerController@update')->middleware('permission:edit.customers');

    #chain
    Route::group(['middleware' => ['permission:view.chains']], function () {
        Route::get('/chains', 'CustomerTypeController@index');
        Route::get('/chain-all', 'CustomerTypeController@indexData');
    });

    Route::post('/chains/save', 'CustomerTypeController@save')->middleware('permission:create.chains');
    Route::post('/chains/update', 'CustomerTypeController@update')->middleware('permission:edit.chains');

    #customer account
    Route::group(['middleware' => ['permission:view.customer.accounts']], function () {
        Route::get('/customer-accounts', 'CustomerCategoryController@index');
        Route::get('/customer-account-all', 'CustomerCategoryController@indexData');
    });

    Route::post('/customer-accounts/save', 'CustomerCategoryController@save')->middleware('permission:create.customer.accounts');
    Route::post('/customer-accounts/update', 'CustomerCategoryController@update')->middleware('permission:edit.customer.accounts');

    #customer carried materials
    Route::group(['middleware' => ['permission:view.customer.carried']], function () {
        Route::get('/customer-carried', 'CustomerMaterialController@index');
        Route::get('/customer-carried-data', 'CustomerMaterialController@indexData');
    });

    Route::post('/customer-carried/setCarried/{customer}/{material}', 'CustomerMaterialController@setCarried')->middleware('permission:set.customer.carried');

    #municipalities
    Route::group(['middleware' => ['permission:view.municipalities']], function () {
        Route::get('/municipalities', 'MunicipalityController@show');
        Route::get('/municipalities/add', 'MunicipalityController@info');
        Route::get('/municipalities/edit', 'MunicipalityController@info');
    });

    Route::post('/municipalities/save', 'MunicipalityController@save')->middleware('permission:create.municipalities');
    Route::post('/municipalities/update', 'MunicipalityController@update')->middleware('permission:edit.municipalities');

    #materials
    Route::group(['middleware' => ['permission:view.materials']], function () {
        Route::get('/materials', 'MaterialController@index');
        Route::get('/material-all', 'MaterialController@indexData');
    });

    Route::post('/materials/save', 'MaterialController@save')->middleware('permission:create.materials');
    Route::post('/materials/update', 'MaterialController@update')->middleware('permission:edit.materials');


    /* reports *************************************************/

    #offtake
    Route::group(['middleware' => ['permission:offtake.view']], function () {
        Route::get('/offtake', 'OfftakeController@index');
        Route::get('/offtake-data', 'OfftakeController@indexData');
        Route::get('/offtake-filter', 'OfftakeController@filter');

        Route::post('/offtake-customer-data', 'OfftakeController@customerData');
    });

    #inventory log (raw)
    Route::group(['middleware' => ['permission:view.inventory.log']], function () {
        Route::get('/reports/inventoryLog', 'ReportsController@inventoryLog');
        Route::get('/reports/inventoryLogTransaction/{transactionNumber}', 'ReportsController@inventoryLogTransaction');
    });


    Route::group(['middleware' => ['permission:view.merchandiser.report']], function () {
        #diser performance
        Route::get('/reports/merchandiser-performance-data', 'ScheduleController@merchandiserPerformanceData');
        Route::get('/reports/merchandiserPerformance', 'ScheduleController@merchandiserPerformance');

        #diser attendance (dtr)
        Route::get('/reports/merchandiserAttendance', 'ScheduleController@merchandiserAttendance');
        Route::get('/reports/merchandiser-attendance-data', 'ScheduleController@merchandiserAttendanceData');
        Route::post('/reports/merchandiser-attendance-pdf', 'ScheduleController@merchandiserAttendancePdf');

        #diser logs (raw)
        Route::get('/reports/merchandiserLog', 'ScheduleController@merchandiserLog');
        Route::get('/reports/merchandiser-log-data', 'ScheduleController@merchandiserLogData');
    });

    /***********************************************************/

    #islands
    Route::get('/island-all', 'IslandController@all');

    Route::get('/reports/offtakePerCustomer', 'ReportsController@offtakePerCustomer');

    #locations
    Route::get('/locations/geofences', 'LocationController@geofences');
    Route::post('/locations/geofences/save', 'LocationController@save');


    Route::get('importExport', 'MaatwebsiteController@importExport');
    Route::get('downloadExcel/{type}', 'MaatwebsiteController@downloadExcel');
    Route::post('importExcel', 'MaatwebsiteController@importExport');


});

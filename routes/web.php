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


Route::group(['middleware' => ['auth', 'role:admin|manager|user']], function () {

    #dashboard
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/getInStore/{date}', 'HomeController@getInStore');
    Route::get('/getInventory/{date}', 'HomeController@getInventory');
    Route::get('/getOfftake/{date}', 'HomeController@getOfftake');
    Route::get('/getSchedule/{date}', 'HomeController@getSchedule');
    Route::get('/getNearExpiry/{date}', 'HomeController@getNearExpiry');
    Route::get('/getRecentlyLogin', 'HomeController@getRecentlyLogin');

    #announcement
    Route::get('/announcements', 'AnnouncementController@show');
    Route::get('/announcements/edit/{id}', 'AnnouncementController@edit');

    Route::post('/announcement/post', 'AnnouncementController@post');
    Route::post('/announcement/add', 'AnnouncementController@add');
    Route::post('/announcements/update/{id}', 'AnnouncementController@update');

    #message
    Route::get('/messages', 'MessageController@index');
    Route::get('/messages-all', 'MessageController@indexData');
    Route::get('/message/chat/{id}', 'MessageController@chat');

    Route::post('/message/addChat', 'MessageController@addChat');
    Route::post('/message/closeMessage', 'MessageController@closeMessage');

    #schedule
    Route::get('/schedules', 'ScheduleController@index');
    Route::get('/schedules-data', 'ScheduleController@indexData');
    Route::get('/schedules/show/{id}', 'ScheduleController@show');
    Route::get('/schedules/records/{merchandiser_id}/{date}', 'ScheduleController@records');
    Route::get('/schedules/edit/{id}', 'ScheduleController@edit');

    Route::group(['middleware' => ['role:admin']], function () {

        #schedule posts
        Route::post('/schedules/save', 'ScheduleController@save');
        Route::post('/schedules/update', 'ScheduleController@update');
        Route::post('/schedules/delete', 'ScheduleController@delete');
        Route::post('/schedules/upload', 'ScheduleController@upload');

        #users posts
        Route::post('/users/save', 'UserController@save');
        Route::post('/users/update', 'UserController@update');

        #agency posts
        Route::post('/agencies/save', 'AgencyController@save');
        Route::post('/agencies/update', 'AgencyController@update');

        #customer posts
        Route::post('/customers/save', 'CustomerController@save');
        Route::post('/customers/update', 'CustomerController@update');

        #chain posts
        Route::post('/chains/save', 'CustomerTypeController@save');
        Route::post('/chains/update', 'CustomerTypeController@update');

        #customer accounts
        Route::post('/customer-accounts/save', 'CustomerCategoryController@save');
        Route::post('/customer-accounts/update', 'CustomerCategoryController@update');

        #customer carried materials posts
        Route::post('/customer-carried/setCarried/{customer}/{material}', 'CustomerMaterialController@setCarried');

        #material posts
        Route::post('/materials/save', 'MaterialController@save');
        Route::post('/materials/update', 'MaterialController@update');
    });


    #users
    Route::get('/users', 'UserController@index');
    Route::get('/users-all', 'UserController@indexData');
//    Route::get('/users', 'UserController@show');
//    Route::get('/users/register', 'UserController@register');
//    Route::get('/users/edit', 'UserController@register');

    #agencies
    Route::get('/agencies', 'AgencyController@index');
    Route::get('/agency-all', 'AgencyController@indexData');

    #customers
    Route::get('/customers', 'CustomerController@index');
    Route::get('/customer-all', 'CustomerController@indexData');

    #chain
    Route::get('/chains', 'CustomerTypeController@index');
    Route::get('/chain-all', 'CustomerTypeController@indexData');

    #customer account
    Route::get('/customer-accounts', 'CustomerCategoryController@index');
    Route::get('/customer-account-all', 'CustomerCategoryController@indexData');

    #customer carried materials
    Route::get('/customer-carried', 'CustomerMaterialController@index');
    Route::get('/customer-carried-data', 'CustomerMaterialController@indexData');


    #municipalities
    Route::get('/municipalities', 'MunicipalityController@show');
    Route::get('/municipalities/add', 'MunicipalityController@info');
    Route::get('/municipalities/edit', 'MunicipalityController@info');

    Route::post('/municipalities/save', 'MunicipalityController@save');
    Route::post('/municipalities/update', 'MunicipalityController@update');

    #materials
    Route::get('/materials', 'MaterialController@index');
    Route::get('/material-all', 'MaterialController@indexData');

    #locations
    Route::get('/locations/geofences', 'LocationController@geofences');
    Route::post('/locations/geofences/save', 'LocationController@save');

    #islands
    Route::get('/island-all', 'IslandController@all');

    /* reports *************************************************/

    #offtake
    Route::get('/offtake', 'OfftakeController@index');
    Route::get('/offtake-all', 'OfftakeController@indexData');


    Route::get('/reports/offtakePerCustomer', 'ReportsController@offtakePerCustomer');

    #inventory log (raw)
    Route::get('/reports/inventoryLog', 'ReportsController@inventoryLog');
    Route::get('/reports/inventoryLogTransaction/{transactionNumber}', 'ReportsController@inventoryLogTransaction');

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

    /***********************************************************/

});


Route::get('importExport', 'MaatwebsiteController@importExport');
Route::get('downloadExcel/{type}', 'MaatwebsiteController@downloadExcel');
Route::post('importExcel', 'MaatwebsiteController@importExport');




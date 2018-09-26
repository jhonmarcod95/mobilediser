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

Route::group(['middleware' => ['auth', 'role:admin']], function () {

    #dashboard
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');

    #announcement
    Route::get('/announcements', 'AnnouncementController@show');
    Route::get('/announcements/edit/{id}', 'AnnouncementController@edit');

    Route::post('/announcement/post', 'AnnouncementController@post');
    Route::post('/announcement/add', 'AnnouncementController@add');
    Route::post('/announcements/update/{id}', 'AnnouncementController@update');

    #message
    Route::get('/message', 'MessageController@show');
    Route::get('/message/chat/{id}', 'MessageController@chat');

    Route::post('/message/addChat', 'MessageController@addChat');
    Route::post('/message/closeMessage', 'MessageController@closeMessage');

    #schedule
    Route::get('/schedules', 'ScheduleController@show');
    Route::get('/schedules/records/{merchandiser_id}/{date}', 'ScheduleController@records');
    Route::get('/schedules/edit/{id}', 'ScheduleController@edit');

    Route::post('/schedules/save', 'ScheduleController@save');
    Route::post('/schedules/update', 'ScheduleController@update');
    Route::post('/schedules/delete', 'ScheduleController@delete');
    Route::post('/schedules/upload', 'ScheduleController@upload');

    #users
    Route::get('/users', 'UserController@show');
    Route::get('/users/register', 'UserController@register');
    Route::get('/users/edit', 'UserController@register');

    Route::post('/users/save', 'UserController@save');
    Route::post('/users/update', 'UserController@update');

    #agencies
    Route::get('/agencies', 'AgencyController@show');
    Route::get('/agencies/add', 'AgencyController@info');
    Route::get('/agencies/edit', 'AgencyController@info');

    Route::post('/agencies/save', 'AgencyController@save');
    Route::post('/agencies/update', 'AgencyController@update');

    #customers
    Route::get('/customers', 'CustomerController@show');
    Route::get('/customers/add', 'CustomerController@info');
    Route::get('/customers/edit', 'CustomerController@info');

    Route::post('/customers/save', 'CustomerController@save');
    Route::post('/customers/update', 'CustomerController@update');

    #customer type
    Route::get('/customers/types', 'CustomerTypeController@show');
    Route::get('/customers/types/add', 'CustomerTypeController@info');
    Route::get('/customers/types/edit', 'CustomerTypeController@info');

    Route::post('/customers/types/save', 'CustomerTypeController@save');
    Route::post('/customers/types/update', 'CustomerTypeController@update');

    #customer category
    Route::get('/customers/categories', 'CustomerCategoryController@show');
    Route::get('/customers/categories/add', 'CustomerCategoryController@info');
    Route::get('/customers/categories/edit', 'CustomerCategoryController@info');

    Route::post('/customers/categories/save', 'CustomerCategoryController@save');
    Route::post('/customers/categories/update', 'CustomerCategoryController@update');

    #customer carried materials
    Route::get('/customers/carried', 'CustomerMaterialController@show');

    #municipalities
    Route::get('/municipalities', 'MunicipalityController@show');
    Route::get('/municipalities/add', 'MunicipalityController@info');
    Route::get('/municipalities/edit', 'MunicipalityController@info');

    Route::post('/municipalities/save', 'MunicipalityController@save');
    Route::post('/municipalities/update', 'MunicipalityController@update');

    #materials
    Route::get('/materials', 'MaterialController@show');
    Route::get('/materials/add', 'MaterialController@info');
    Route::get('/materials/edit', 'MaterialController@info');

    Route::post('/materials/save', 'MaterialController@save');
    Route::post('/materials/update', 'MaterialController@update');

    #locations
    Route::get('/locations/geofences', 'LocationController@geofences');
    Route::post('/locations/geofences/save', 'LocationController@save');


    #reports
    Route::get('/reports/offtakePerCustomer', 'ReportsController@offtakePerCustomer');
    Route::get('/reports/merchandiserLog', 'ReportsController@merchandiserLog');
    Route::get('/reports/inventoryLog', 'ReportsController@inventoryLog');
    Route::get('/reports/inventoryLogTransaction/{transactionNumber}', 'ReportsController@inventoryLogTransaction');
    Route::get('/reports/merchandiserAttendance', 'ReportsController@merchandiserAttendance');





});



Route::get('importExport', 'MaatwebsiteController@importExport');
Route::get('downloadExcel/{type}', 'MaatwebsiteController@downloadExcel');
Route::post('importExcel', 'MaatwebsiteController@importExport');




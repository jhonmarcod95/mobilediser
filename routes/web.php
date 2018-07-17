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


Route::group(['middleware' => 'auth'], function () {

    #dashboard
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::post('/announcement/add', 'AnnouncementController@add');

    #message
    Route::get('/message', 'MessageController@show');
    Route::get('/message/chat/{id}', 'MessageController@chat');

    Route::post('/message/addChat', 'MessageController@addChat');
    Route::post('/message/closeMessage', 'MessageController@closeMessage');

    #schedule
    Route::get('/schedules', 'ScheduleController@show');
    Route::get('/schedules/records', 'ScheduleController@records');
    Route::post('/schedules/save', 'ScheduleController@save');
    Route::post('/schedules/delete', 'ScheduleController@delete');


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
    //Route::get('/locations', 'LocationController@show');
    Route::get('/locations/geofences', 'LocationController@geofences');

    Route::post('/locations/geofences/save', 'LocationController@save');

    #errors
    Route::get('/errors', function(){
        return view('layouts.errors');
    });

    Route::get('/map', function(){
        $config = array();
        $config['center'] = 'auto';
        $config['onboundschanged'] = 'if (!centreGot) {
            var mapCentre = map.getCenter();
            marker_0.setOptions({
                position: new google.maps.LatLng(37.429, -122.1419)
            });
        }
        centreGot = true;';

        app('map')->initialize($config);

        // set up the marker ready for positioning
        // once we know the users location
        $marker = array();
        app('map')->add_marker($marker);

        $map = app('map')->create_map();
        echo "<html><head><script type=\"text/javascript\">var centreGot = false;</script>".$map['js']."</head><body>".$map['html']."</body></html>";
});


});





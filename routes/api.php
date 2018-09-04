<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('user', ['middleware' => 'auth:api', 'uses' => 'Api\UserController@show']);
Route::get('search', ['middleware' => 'auth:api', 'uses' => 'Api\UserController@search']);
// Route::get('query', ['middleware' => 'auth:api', 'uses' => 'Api\QueryController@query']);

Route::post('insert', ['middleware' => 'auth:api', 'uses' => 'Api\UserController@insert']);
Route::post('update', ['middleware' => 'auth:api', 'uses' => 'Api\UserController@update']);
Route::post('delete', ['middleware' => 'auth:api', 'uses' => 'Api\UserController@delete']);


Route::post('query', ['middleware' => 'auth:api', 'uses' => 'Api\QueryController@query']);
Route::post('exec', ['middleware' => 'auth:api', 'uses' => 'Api\QueryController@execute']);
Route::post('inserttransaction', ['middleware' => 'auth:api', 'uses' => 'Api\QueryController@inserttransaction']);
Route::post('inserttransaction/image', ['middleware' => 'auth:api', 'uses' => 'Api\QueryController@insertTransactionImage']);
Route::post('transactionNumber', ['middleware' => 'auth:api', 'uses' => 'Api\QueryController@transactionNumber']);

Route::post('location/save', ['middleware' => 'auth:api', 'uses' => 'Api\LocationController@save']);
Route::post('location/check', ['middleware' => 'auth:api', 'uses' => 'Api\LocationController@check']);

Route::post('login', ['uses' => 'Api\MerchandiserController@login']);
Route::post('updatecontactinformation', ['middleware' => 'auth:api', 'uses' => 'Api\MerchandiserController@updatecontactinformation']);
Route::post('changepassword', ['middleware' => 'auth:api', 'uses' => 'Api\MerchandiserController@changepassword']);

Route::post('announcementreadby', ['middleware' => 'auth:api', 'uses' => 'Api\AnnouncementReadByController@insertreadby']);

Route::post('attendanceimage/upload', ['middleware' => 'auth:api', 'uses' => 'Api\AttendanceImageController@upload']);

Route::post('transaction/getBeginningBalance', ['middleware' => 'auth:api', 'uses' => 'Api\TransactionOfftakeController@getBeginningBalance']);
Route::post('transaction/addTransactionOfftake', ['middleware' => 'auth:api', 'uses' => 'Api\TransactionOfftakeController@addTransactionOfftake']);
Route::post('transaction/backgroundTransactionOfftake', ['middleware' => 'auth:api', 'uses' => 'Api\TransactionOfftakeController@backgroundTransactionOfftake']);




#for testing purposes only
Route::post('toBCrpyt', ['middleware' => 'auth:api', 'uses' => 'Api\UserController@toBCrpyt']);
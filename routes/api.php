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


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group(['middleware' => 'auth:api'], function() {
    // Route::group(['middleware' => 'SessionToken'], function() {
        Route::get('logout', 'AuthController@logout');
    });
    	Route::get('user', 'AuthController@user');
        Route::get('destroy', 'AuthController@destroy');
        


});
Route::post('/post-survey-web','ApiController@postSurvey');
Route::group(['middleware'=>'auth:api'],function(){

    // Survey
    Route::post('/save-survey', 'FormController@saveSurvey');
    Route::post('/save-linked', 'FormController@saveLinked');
    Route::post('/save-klasifikasi', 'FormController@saveKlasifikasi');
    Route::post('/save-soal-resp', 'FormController@saveSoalResponden');
    Route::post('/delete-survey/{id}','FormController@deleteSurvey')->name('delete_survey');
    Route::post('/update-survey/{id}', 'FormController@updateSurvey');
    Route::post('/copy-survey/{id}', 'FormController@copySurvey');

    // Devices
    Route::post('/save-devices', 'FormController@saveDevices');
    Route::post('/update-devices/{id}', 'FormController@updateDevices');
    Route::post('/delete-devices-survey/{id}', 'FormController@deleteDevicesSurvey');
    Route::post('/delete-devices/{id}', 'FormController@deleteDevices');

    // Reusable
    Route::post('/save-pertanyaan', 'FormController@savePertanyaan');
    Route::post('/update-pertanyaan/{id}', 'FormController@updatePertanyaan');
    Route::post('/delete-pertanyaan/{id}', 'FormController@deletePertanyaan');


    // Interviewer
    Route::post('save-interviewer', 'FormController@saveInterviewer')->name('add_interviewer');
    Route::post('update-interviewer', 'FormController@updateInterviewer')->name('edit_interviewer');
    Route::post('delete-interviewer', 'FormController@deleteInterviewer')->name('delete_interviewer');

    /*Api User*/
    Route::post('tambah-user', 'FormController@tambahUser')->name('form_tambah_user');
    Route::post('edit-user', 'FormController@editUser')->name('form_edit_user');
    Route::post('status-user', 'FormController@statusUser')->name('form_status_user');
    Route::post('delete-user', 'FormController@deleteUser')->name('form_delete_user');

});

Route::post('/sync-device','ApiController@cekImei');
    // Route::post('/cek-imei','ApiController@cekImei');
Route::group(['middleware' => 'token_device'], function () {
    Route::get('/get-survey/{id}','ApiController@getSurvey');
    Route::post('/post-survey/{id}','ApiController@postSurvey');
});

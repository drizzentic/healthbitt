<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});

Route::group(['prefix'=>'api'],function(){

	Route::get('patientdata/{id}','PatientDataController@show');

	Route::get('patientdata/prescriptions/{id}','PatientDataController@prescriptions');
	Route::get('patientdata/labtest/{id}','PatientDataController@labtest');
	Route::get('patientdata/imaging/{id}','PatientDataController@imaging');
	Route::get('patientdata/diagnoses/{id}','PatientDataController@diagnoses');
	Route::get('patientdata/metadata/{id}','PatientDataController@metadata');
	Route::post('store_patient_data','PatientDataController@store');
});

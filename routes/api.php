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

Route::group(['middleware' => ['Cors']], function () {
	Route::namespace('Admin\Api')->group(function () {
		Route::prefix('admin')->group(function () {
			
			Route::group(['middleware' => ['jwt.verify']], function () {
				Route::post('checkadminlogin', 'AuthController@me');
			});

				//Login Apis
				Route::post('getlogindata','AuthController@get_admin_login_data')->name('getlogindata'); 
				Route::post('forgot_password','AuthController@get_forgot_password_data')->name('forgot_password'); 
				Route::get('logout','AuthController@logout')->name('logout'); 

				//User List
				Route::get('userlist', 'UserController@userList');
				Route::post('getuserinfo', 'UserController@getUserInfo');
				Route::post('adduserdata', 'UserController@addUserData');
				Route::post('updateuserdata', 'UserController@updateUserData');

				//Offer Api
				Route::get('offerlist', 'OfferController@offerList');
				Route::post('addoffer', 'OfferController@addOffer');
				Route::post('updateoffer', 'OfferController@updateOffer');
				Route::post('getofferinfo', 'OfferController@getOfferInfo');

				//NABH Indicators
				Route::get('indicatorslist', 'NabhIndicatorsController@indicatorsList');
				Route::post('addindicators', 'NabhIndicatorsController@addIndicators');
				Route::post('updateindicators', 'NabhIndicatorsController@updateIndicators');
				Route::post('getindicatorsinfo', 'NabhIndicatorsController@getIndicatorsInfo');

				//NABH Group
				Route::get('nabhgrouplist', 'NabhGroupController@nabhGroupList');
				Route::post('addnabhgroup', 'NabhGroupController@addNabhGroup');
				Route::post('updatenabhgroup', 'NabhGroupController@updateNabhGroup');

				//Hospital Registration
				Route::get('hospitallist', 'HospitalRegistrationController@hospitalList');
				Route::post('addhospital', 'HospitalRegistrationController@getHospitalInfo');
				Route::post('updatehospital', 'HospitalRegistrationController@addHospitalData');
				Route::post('gethospitalinfo', 'HospitalRegistrationController@updateHospitalData');

				//Role Apis
				Route::get('rolelist', 'RoleController@roleList');

				//Packages
				Route::get('package/getlist', 'PackageController@List');
				Route::post('package/adddata', 'PackageController@Add');
				Route::post('package/editdata', 'PackageController@Edit');
				Route::post('package/getinfo', 'PackageController@getInfo');

		});
		
		
	});


	//This  api for hospital
	Route::namespace('Hospital\Api')->group(function () {
		Route::prefix('hospital')->group(function () {
			
			Route::group(['middleware' => ['jwt.hospital.verify']], function () {
				Route::post('checkhospitallogin', 'AuthController@me');
			});

			//Login Apis
			Route::post('gethospitallogindata','AuthController@get_login_data')->name('gethospitallogindata'); 
			Route::post('hospital_forgot_password','AuthController@get_forgot_password_data')->name('hospital_forgot_password');

			Route::get('logout','AuthController@logout')->name('logout'); 

			Route::post('registration', 'HospitalRegistrationController@addHospitalData');

			//For adding and updating indicators data
			Route::get('get_indicators_input', 'NabhIndicatorsController@getIndicatorsInput');
			Route::post('saveindicatorsdata', 'NabhIndicatorsController@savendicatorsData');
			Route::get('getindicatorlist', 'NabhIndicatorsController@getIndicatorsList');
			Route::post('getindicatordata', 'NabhIndicatorsController@getIndicatorData');

			//Indicator List
			Route::get('indicatorlist', 'NabhIndicatorsController@indicatorsList');

			//Hospital Users
			Route::get('users/getlist', 'HospitalUsersController@List');
			Route::post('users/adddata', 'HospitalUsersController@Add');
			Route::post('users/editdata', 'HospitalUsersController@Edit');
			Route::post('users/getinfo', 'HospitalUsersController@getInfo');
			Route::post('users/assignindicators', 'HospitalUsersController@AssignIndicators');

			//Hospital Packages
			Route::get('packages/getlist', 'HospitalPackagesController@List');


		});
	});

});
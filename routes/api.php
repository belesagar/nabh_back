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

				//Nabh Form data
				Route::get('form/list', 'NabhIndicatorsController@formList');
				Route::post('formdata/add', 'NabhIndicatorsController@addFormData');
				Route::post('getformdata', 'NabhIndicatorsController@getformInfo');
				Route::post('update/formdata', 'NabhIndicatorsController@updateFormData');

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

			//Hospital Info
			Route::get('info', 'HospitalRegistrationController@getInfo');

			//For adding and updating indicators data
			Route::post('get_indicators_input', 'NabhIndicatorsController@getIndicatorsInput');
			Route::post('saveindicatorsdata', 'NabhIndicatorsController@savendicatorsData');
			Route::get('getindicatorlist', 'NabhIndicatorsController@getIndicatorsList');
			Route::post('getindicatordata', 'NabhIndicatorsController@getIndicatorFormDataList');
			Route::post('indicator/formdata', 'NabhIndicatorsController@getIndicatorFormData');
			Route::post('indicator/formdata/details', 'NabhIndicatorsController@getIndicatorFormDataDetails');
			Route::post('update/indicatorsdata', 'NabhIndicatorsController@updateIndicatorFormData');

			//Indicator List
			Route::get('indicatorlist', 'NabhIndicatorsController@indicatorsList');

			//Hospital Users
			Route::post('users/getlist', 'HospitalUsersController@List');
			Route::post('users/adddata', 'HospitalUsersController@Add');
			Route::post('users/editdata', 'HospitalUsersController@Edit');
            Route::post('profile/change-password', 'HospitalUsersController@changePassword');
            Route::get('users/getinfo', 'HospitalUsersController@getInfo');
            Route::post('profile/savedata', 'HospitalUsersController@saveProfileData');
            Route::get('users/indicators/list/{id}', 'HospitalUsersController@GetUserAssignIndicators');
			Route::match(['get', 'post'],'user/permission/{id}', 'HospitalUsersController@UserAssignIndicators');

			//Hospital Packages
			Route::get('packages/getlist', 'HospitalPackagesController@List');
			Route::post('package/details', 'HospitalPackagesController@packageDetails');

			//Assign Indicators
			Route::post('packages/acceptindicators', 'NabhIndicatorsController@AcceptIndicators');
			Route::get('packages/getacceptindicators/list', 'NabhIndicatorsController@ListofAcceptIndicators');

			//Doctors Route
			Route::post('doctors/getlist', 'DoctorsController@List');
			Route::post('doctor/adddata', 'DoctorsController@Add');
			Route::post('doctor/editdata', 'DoctorsController@Edit');
			Route::post('doctor/getinfo', 'DoctorsController@getInfo');
			Route::get('doctors/type/getlist', 'DoctorsController@typeList');

			//OT Route
			Route::get('ot/getlist', 'OtController@List');

			//Payments
			Route::post('payment/initiate', 'HospitalTransactionController@initiatePayment');
			Route::post('payment/check', 'HospitalTransactionController@checkPayment');

            //Patient
            Route::post('patient/list', 'HospitalPatientController@List');
            Route::post('patient/adddata', 'HospitalPatientController@Add');
            Route::post('patient/editdata', 'HospitalPatientController@Edit');
            Route::post('patient/getinfo', 'HospitalPatientController@getInfo');

            //Virtual Hospital
            Route::post('virtual/hospital', 'VirtualHospitalController@addVirtualHospitalData');
            Route::get('virtual/data', 'VirtualHospitalController@getVirtualHospitalData');
            
        });
	});

});

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
			Route::post('getindicatorlist', 'NabhIndicatorsController@getIndicatorsList');
			Route::post('getindicatordata', 'NabhIndicatorsController@getIndicatorFormDataList');
			Route::post('indicator/formdata', 'NabhIndicatorsController@getIndicatorFormData');
			Route::post('indicator/formdata/details', 'NabhIndicatorsController@getIndicatorFormDataDetails');
			Route::post('update/indicatorsdata', 'NabhIndicatorsController@updateIndicatorFormData');

			//Indicator List
			Route::post('indicatorlist', 'NabhIndicatorsController@indicatorsList');

			Route::group(['middleware' => ['hospitalUserPermission']], function () {
				//Hospital Users
				Route::post('users/getlist', 'HospitalUsersController@List')->name("view_key-hospital_user_list");
				Route::post('users/adddata', 'HospitalUsersController@Add')->name("add_key-hospital_user_add");
				Route::post('users/editdata', 'HospitalUsersController@Edit')->name("edit_key-hospital_user_edit");
	            Route::post('profile/change-password', 'HospitalUsersController@changePassword');
	            Route::post('users/getinfo', 'HospitalUsersController@getUserInfo')->name("view_key-hospital_user_list");

	            //Doctors Route
				Route::post('doctors/getlist', 'DoctorsController@List')->name("view_key-hospital_doctor_list");
				Route::post('doctor/adddata', 'DoctorsController@Add')->name("add_key-hospital_doctor_add");
				Route::post('doctor/editdata', 'DoctorsController@Edit')->name("edit_key-hospital_doctor_edit");
				Route::post('doctor/getinfo', 'DoctorsController@getInfo')->name("view_key-hospital_doctor_list");
				Route::get('doctors/type/getlist', 'DoctorsController@typeList')->name("view_key-hospital_doctor_list");

				//Menu Route
	            Route::get('role/list', 'HospitalPermissionController@hospitalRoleList')->name("view_key-hospital_role_list");
	            Route::post('role/add', 'HospitalRoleController@Add')->name("add_key-hospital_role_add");
	            Route::post('role/edit', 'HospitalRoleController@Edit')->name("edit_key-hospital_role_edit");

	            //Review Meeting
	            Route::post('review-meeting/list', 'HospitalReviewMeetingController@List')->name("view_key-hospital_review_meeting_list");
	            Route::post('review-meeting/add', 'HospitalReviewMeetingController@Add')->name("add_key-hospital_review_meeting_add");
	            Route::post('review-meeting/edit', 'HospitalReviewMeetingController@Edit')->name("edit_key-hospital_review_meeting_edit");
	            Route::post('review-meeting/getinfo', 'HospitalReviewMeetingController@getInfo')->name("view_key-hospital_review_meeting_list");

	            //Patient
	            Route::post('patient/list', 'HospitalPatientController@List')->name("view_key-hospital_patient_list");
	            Route::post('patient/adddata', 'HospitalPatientController@Add')->name("add_key-hospital_patient_add");
	            Route::post('patient/editdata', 'HospitalPatientController@Edit')->name("edit_key-hospital_patient_edit");
	            Route::post('patient/getinfo', 'HospitalPatientController@getInfo')->name("view_key-hospital_patient_list");

            });
            Route::get('users/profile-data', 'HospitalUsersController@getInfo');
            Route::post('profile/savedata', 'HospitalUsersController@saveProfileData');
            Route::get('users/indicators/list/{id}', 'HospitalUsersController@GetUserAssignIndicators');
			Route::post('user/indicator/assign', 'HospitalUsersController@UserAssignIndicators');

			//Hospital Packages
			Route::get('packages/getlist', 'HospitalPackagesController@List');
			Route::post('package/details', 'HospitalPackagesController@packageDetails');

			//Assign Indicators
			Route::post('packages/acceptindicators', 'NabhIndicatorsController@AcceptIndicators');
			Route::get('packages/getacceptindicators/list', 'NabhIndicatorsController@ListofAcceptIndicators');
			Route::get('user/indicators/list', 'NabhIndicatorsController@getHospitalUserIndicators');


			//OT Route
			Route::get('ot/getlist', 'OtController@List');

			//Payments
			Route::post('payment/initiate', 'HospitalTransactionController@initiatePayment');
			Route::post('payment/check', 'HospitalTransactionController@checkPayment');

            //Virtual Hospital
            Route::post('virtual/hospital', 'VirtualHospitalController@addVirtualHospitalData');
            Route::get('virtual/data', 'VirtualHospitalController@getVirtualHospitalData');
            Route::get('virtual/floor-data', 'VirtualHospitalController@getVirtualHospitalFloorData');
            Route::post('virtual/add-floor-data', 'VirtualHospitalController@addVirtualfloorData');
            Route::post('virtual/get-floor-data', 'VirtualHospitalController@getfloorDataByFloorNumber');
            
            //Reports
            Route::post('indicator/report', 'HospitalReportsController@createChartDataOfIndicator');
            Route::post('indicator/excel-report-list', 'HospitalReportsController@getIndicatorExcelList');
            Route::post('indicator/excel-report-data', 'HospitalReportsController@getIndicatorExcelReportData')->name("report_data");

            //Menu Route
            Route::get('menu/list', 'HospitalPermissionController@hospitalMenuList');
            Route::post('check/permission', 'HospitalPermissionController@hospitalCheckPermission');
            Route::post('role/permission/menu/list', 'HospitalRoleController@roleMenuPermissionList');
            Route::post('role/permission/menu/add', 'HospitalRoleController@roleMenuPermissionAdd');
            Route::get('user/role/list', 'HospitalPermissionController@hospitalUserRoleList');

        });
	});

});

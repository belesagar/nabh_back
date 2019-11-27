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

//This for admin login
Route::namespace('Admin')->group(function () {
    Route::prefix('admin')->group(function () {
        //This is login route
        Route::get('downloadexcel', 'AuthController@downloadExcel');
        Route::get('drawing', 'AuthController@drawings');
        Route::get('downloadpdf', 'AuthController@downloadPdf');
        Route::get('login', 'AuthController@login')->name('admin.login');
        Route::get('signup', 'AuthController@signup')->name('admin.signup');
        Route::get('forgot_password', 'AuthController@forgot_password')->name('admin.forgot_password');
        Route::get('reset_password/{id}', 'AuthController@get_reset_password_data')->name('admin.reset_password');
        Route::post('get_login_data', 'AuthController@get_admin_login_data')->name('get_admin_login_data');
        Route::post('get_signup_data', 'AuthController@get_admin_signup_data')->name('get_admin_signup_data');
        Route::post('get_forgot_password_data', 'AuthController@get_forgot_password_data')->name('get_forgot_password_data');
        Route::post('set_reset_password', 'AuthController@set_reset_password')->name('set_reset_password');

        //This is after login route
        Route::group(['middleware' => ['CheckAdminLogin']], function () {
            Route::get('logout', 'AuthController@logout')->name('admin.logout');
            Route::get('dashboard', 'DashboardController@dashboard')->name('admin.dashboard');
            Route::get('userlist', 'UserListController@userList')->name('admin.userlist');
            Route::get('user/operation/{id?}', 'UserListController@userOperation')->name('admin.useroperation');
        });
    });
});
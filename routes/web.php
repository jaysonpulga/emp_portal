<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return view('home');
    });

    Route::get('home', 'HomeController@index')->name('home');

    Route::post('dailyactivityplanner/getdailyactivity', 'DailyActivityPlannerController@getdailyactivity')->name('dailyactivityplanner.getdailyactivity');

    Route::get('dailyactivityplanner', 'DailyActivityPlannerController@index')->name('dailyactivityplanner.index');
    Route::post('dailyactivityplanner/store', 'DailyActivityPlannerController@store')->name('dailyactivityplanner.store');
    Route::post('dailyactivityplanner/update', 'DailyActivityPlannerController@update')->name('dailyactivityplanner.update');
    Route::get('dailyactivityplanner/destroy/{id}', 'DailyActivityPlannerController@destroy');
    Route::get('dailyactivityplanner/{id}/edit', 'DailyActivityPlannerController@edit');
    Route::post('dailyactivityplanner/getdap', 'DailyActivityPlannerController@getdap')->name('dailyactivityplanner.getdap');

    Route::post('dailymovementtracker/getdailymovement', 'DailyMovementTrackerController@getdailymovement')->name('dailymovementtracker.getdailymovement');

    Route::get('dailymovementtracker', 'DailyMovementTrackerController@index')->name('dailymovementtracker.index');
    Route::post('dailymovementtracker/store', 'DailyMovementTrackerController@store')->name('dailymovementtracker.store');
    Route::post('dailymovementtracker/update', 'DailyMovementTrackerController@update')->name('dailymovementtracker.update');
    Route::get('dailymovementtracker/destroy/{id}', 'DailyMovementTrackerController@destroy');
    Route::get('dailymovementtracker/{id}/edit', 'DailyMovementTrackerController@edit');

    Route::get('user/profile', 'UserProfileController@index')->name('profile.index');
    Route::post('user/profile/store', 'UserProfileController@store')->name('profile.post');

    Route::get('user/password', 'ChangePasswordController@index')->name('password.index');
    Route::post('user/password/update', 'ChangePasswordController@update')->name('password.update');

//    Route::group(['middleware' => 'is_admin'], function () {
        Route::get('admin/company', 'CompanyController@index')->name('company.index');
        Route::post('admin/company/store', 'CompanyController@store')->name('company.store');
        Route::post('admin/company/update', 'CompanyController@update')->name('company.update');
        Route::get('admin/company/destroy/{id}', 'CompanyController@destroy');
        Route::get('admin/company/{id}/edit', 'CompanyController@edit');

        Route::get('admin/workhour', 'WorkHourController@index')->name('workhour.index');
        Route::post('admin/workhour/store', 'WorkHourController@store')->name('workhour.store');
        Route::post('admin/workhour/update', 'WorkHourController@update')->name('workhour.update');
        Route::get('admin/workhour/destroy/{id}', 'WorkHourController@destroy');
        Route::get('admin/workhour/{id}/edit', 'WorkHourController@edit');

        Route::get('downloaddmt', 'DownloadDmtController@index')->name('downloaddmt.index');
        Route::post('downloaddmt/getdmt', 'DownloadDmtController@getdmt')->name('downloaddmt.getdmt');

        Route::get('admin/resetuserpassword', 'ResetUserPasswordController@index')->name('resetuserpassword.index');
        Route::post('admin/resetuserpassword/reset', 'ResetUserPasswordController@reset')->name('resetuserpassword.reset');

//    });
});

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

Route::get('/greeting', function () {
    return 'Hello World';
});


Route::group(['namespace' => 'Auth'], function () {

    Route::get('/login', 'LoginController@login')->name('login');
    Route::post('/login', 'LoginController@Authenticate');
    Route::get('/logout', 'LoginController@logout');
    Route::get('/forgot-password', 'LoginController@ForgotPasswordView');
    Route::post('/forgot-password', 'LoginController@sendResetLinkEmail');
    Route::get('/reset-password', 'LoginController@ResetView');
    Route::post('/reset-password', 'LoginController@ResetPassword');
    Route::get('/admin/create', 'LoginController@RegisterUser');
});


Route::group(['middleware' => 'auth'], function () {
    // Route::get('/users/{any?}', 'HomeController@index')->where('any', '.*');
    // Route::get('/user/list', 'UserController@list');
    // Route::post('/user', 'UserController@store');
    // Route::get('/user/{id}', 'UserController@fetch');
    // Route::put('/user', 'UserController@update');

    Route::get('/dashboard-data', 'HomeController@dashboardData');
    Route::get('/dashboard/expired-bills', 'HomeController@expired_bills');
    Route::get('/dashboard/recently-added-customers', 'HomeController@recently_added_customers');

    Route::group(['prefix' => '/role', 'middleware' => ['role:Super Admin']], function () {
        Route::get('/list', 'RoleController@list');
        Route::post('/edit/{id}', 'RoleController@update');
        Route::post('/create', 'RoleController@store');
        Route::get('/fetch/{id}', 'RoleController@fetch');
        Route::get('/get-permissions', 'RoleController@fetchPermissions');
        Route::post('/{id}/update/permissions', 'RoleController@update_permissions');
        Route::delete('/{id}', 'RoleController@destroy');
    });

    Route::group(['prefix' => '/permission', 'middleware' => ['role:Super Admin']], function () {
        Route::get('/list', 'PermissionController@list');
        Route::post('/edit/{id}', 'PermissionController@update');
        Route::post('/create', 'PermissionController@store');
        Route::get('/fetch/{id}', 'PermissionController@fetch');
        Route::delete('/{id}', 'PermissionController@destroy');
    });


    Route::group(['prefix' => '/customer'], function () {
        Route::post('/list', 'CustomerController@list');
        Route::get('/bills', 'CustomerController@bills');
        Route::post('/edit/{id}', 'CustomerController@update');
        Route::post('/create', 'CustomerController@store');
        Route::get('/fetch/{id}', 'CustomerController@fetch');
        Route::post('/{id}/update-service', 'CustomerController@update_service');
        Route::delete('/{id}', 'CustomerController@destroy')->middleware(['role:Super Admin']);
        Route::delete('/service-type/{id}', 'CustomerController@service_type_destroy')->middleware(['role:Super Admin']);
    });


    Route::group(['prefix' => '/service-type', 'middleware' => ['role:Super Admin']], function () {
        Route::post('/list', 'ServiceTypeController@list');
        Route::post('/edit/{id}', 'ServiceTypeController@update');
        Route::post('/create', 'ServiceTypeController@store');
        Route::get('/fetch/{id}', 'ServiceTypeController@fetch');
        Route::delete('/{id}', 'ServiceTypeController@destroy');
    });


    Route::group(['prefix' => '/user', 'middleware' => ['role:Super Admin']], function () {
        Route::get('/list', 'UserController@list');
        Route::post('/edit/{id}', 'UserController@update');
        Route::post('/create', 'UserController@store');
        Route::get('/fetch/{id}', 'UserController@fetch');
        Route::get('/fetch-roles', 'UserController@fetch_roles');
        Route::post('/update-status/{id}', 'UserController@update_status');
    });

    Route::group(['prefix' => '/email', 'middleware' => ['role:Super Admin']], function () {
        Route::post('/create', 'MailController@store');
        Route::get('/list', 'MailController@getMail');
    });
    
    

    Route::get('{path}', 'HomeController@index')->where('path', '(.*)');
});


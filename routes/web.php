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

Route::get('/',  function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware('auth')->group(function () {

    Route::get('/', 'HomeController@index')->name('dashboard');

    // Access Level 1
    Route::middleware('checkRole:1')->group(function () {
        Route::resource('user', 'Admin\UsersController', ['names' => ['index' => 'user', 'create' => 'user', 'edit' => 'user']]);
        Route::resource('role', 'Admin\RolesController', ['names' => ['index' => 'role', 'create' => 'role', 'edit' => 'role']]);
        Route::resource('company', 'Admin\CompanyController', ['names' => ['index' => 'company', 'create' => 'company', 'edit' => 'company']]);

        Route::prefix('settings')->group(function () {
            $url = 'Admin\SettingsController';

            Route::get('/', $url.'@index')->name('settings');
            Route::post('/', $url.'@update')->name('settings');
        });

        Route::prefix('company-project')->group(function () {
            $url = 'Admin\CompanyProjectsController';

            Route::get('/{company}', $url.'@index')->where('company', '[0-9]+')->name('company');
            Route::get('/{company}/create', $url.'@create')->where('company', '[0-9]+')->name('company');
            Route::get('/edit/{companyProject}', $url.'@edit')->where('companyProject', '[0-9]+')->name('company');
            Route::post('/{company}', $url.'@store')->where('company', '[0-9]+');
            Route::put('/{companyProject}', $url.'@update')->where('companyProject', '[0-9]+');
            Route::delete('/{companyProject}', $url.'@destroy')->where('companyProject', '[0-9]+');
        });

        Route::prefix('activity-log')->group(function () {
            $url = 'Admin\ActivityLogsController';

            Route::get('/', $url.'@index')->name('activitylog');
        });
    });

    // Access Level 1 and 2
    Route::middleware('checkRole:1|2')->group(function () {
        Route::resource('coa-tagging', 'Admin\CoaTaggingController', ['names' => ['index' => 'coatagging', 'create' => 'coatagging', 'edit' => 'coatagging']]);
        Route::resource('expense-type', 'Admin\ExpenseTypesController', ['names' => ['index' => 'expensetype', 'create' => 'expensetype', 'edit' => 'expensetype']]);
        Route::resource('particular', 'Admin\ParticularsController', ['names' => ['index' => 'particular', 'create' => 'particular', 'edit' => 'particular']]);
    });

    // Access Level 1, 2, and 3
    Route::middleware('checkRole:1|2|3')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';

            Route::get('/create/{trans_type}/{trans_company}', $url.'@create')->where('trans_company', '[0-9]+');
            Route::post('/create', $url.'@store');
            Route::get('/edit/{transaction}', $url.'@edit')->where('transaction', '[0-9]+');
            Route::put('/edit/{transaction}', $url.'@update')->where('transaction', '[0-9]+');
            Route::get('/view/{transaction}', $url.'@show')->where('transaction', '[0-9]+');
            Route::get('/reset/{transaction}', $url.'@reset')->where('transaction', '[0-9]+');
            Route::put('/cancel/{transaction}', $url.'@cancel')->where('transaction', '[0-9]+');
            // Route::put('/issue/{transaction}', $url.'@issue')->where('transaction', '[0-9]+');
            Route::get('/report/', $url.'@report')->middleware('checkRole:1|2');

            Route::get('/{trans_page}/{trans_company?}', $url.'@index')->where('trans_company', '[0-9]+');
        });

        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';
            
            Route::get('/create', $url.'@create');
            // Route::post('/create', $url.'@store');
            // Route::get('/edit/{transaction}', $url.'@edit')->where('transaction', '[0-9]+');
            // Route::put('/edit/{transaction}', $url.'@update')->where('transaction', '[0-9]+');
            // Route::get('/view/{transaction}', $url.'@show')->where('transaction', '[0-9]+');
            // Route::get('/reset/{transaction}', $url.'@reset')->where('transaction', '[0-9]+');
            // Route::put('/cancel/{transaction}', $url.'@cancel')->where('transaction', '[0-9]+');
            // Route::put('/issue/{transaction}', $url.'@issue')->where('transaction', '[0-9]+');
            // Route::get('/report/', $url.'@report')->middleware('checkRole:1|2');

            Route::get('/{trans_page}/{trans_company?}', $url.'@index')->where('trans_company', '[0-9]+');
        });
    });
});
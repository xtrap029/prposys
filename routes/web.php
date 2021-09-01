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
    Route::get('/', 'Main\ChooseAppController@index')->name('chooseapp');
    
    Route::get('/sequence-dashboard', 'Admin\DashboardController@index')->name('sequence-dashboard');
    Route::get('/people-dashboard', 'People\DashboardController@index')->name('people-dashboard');
    Route::get('/leaves-dashboard', 'Leaves\DashboardController@index')->name('leaves-dashboard');
    
    // Access Level 1
    Route::middleware('checkRole:1')->group(function () {

        // Sequence

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

        Route::prefix('control-panel')->group(function() {
            $url = 'Admin\ControlPanelsController';

            Route::get('/revert-status', $url.'@revert_status')->name('revertstatus');
            Route::post('/revert-status', $url.'@revert_status_store');
            Route::get('/force-cancel', $url.'@force_cancel')->name('forcecancel');
            Route::post('/force-cancel', $url.'@force_cancel_store');
        });

        // People

        Route::resource('user', 'People\UsersController', ['names' => ['index' => 'user', 'create' => 'user', 'edit' => 'user']]);
        
        Route::prefix('people-settings')->group(function () {
            $url = 'People\SettingsController';

            Route::get('/', $url.'@index')->name('people-settings');
            Route::post('/', $url.'@update')->name('people-settings');
        });

        Route::prefix('activity-log')->group(function () {
            $url = 'People\ActivityLogsController';

            Route::get('/', $url.'@index')->name('activitylog');
        });

        Route::get('/db-backups', 'People\DBBackupsController@db_backups')->name('dbbackups');
        Route::get('/db-backups-zip', 'People\DBBackupsController@db_backups_zip')->name('dbbackups');
        Route::get('/db-backups-generate', 'People\DBBackupsController@db_backups_generate')->name('dbbackups');

        // Leaves

        Route::prefix('leaves-settings')->group(function () {
            $url = 'Leaves\SettingsController';

            Route::get('/', $url.'@index')->name('leaves-settings');
            Route::post('/', $url.'@update')->name('leaves-settings');
        });
    });

    // Access Level 1 and 2
    Route::middleware('checkRole:1|2')->group(function () {
        Route::resource('coa-tagging', 'Admin\CoaTaggingController', ['names' => ['index' => 'coatagging', 'create' => 'coatagging', 'edit' => 'coatagging']]);
        Route::resource('expense-type', 'Admin\ExpenseTypesController', ['names' => ['index' => 'expensetype', 'create' => 'expensetype', 'edit' => 'expensetype']]);
        Route::resource('particular', 'Admin\ParticularsController', ['names' => ['index' => 'particular', 'create' => 'particular', 'edit' => 'particular']]);
        Route::resource('vat-type', 'Admin\VatTypesController', ['names' => ['index' => 'vattype', 'create' => 'vattype', 'edit' => 'vattype']]);
        Route::resource('released-by', 'Admin\ReleasedByController', ['names' => ['index' => 'releasedby', 'create' => 'releasedby', 'edit' => 'releasedby']]);
        
        Route::resource('bank', 'Admin\BanksController', ['names' => ['index' => 'bank', 'create' => 'bank', 'edit' => 'bank'], 'asd' => ['index' => 'bank1', 'create' => 'bank1', 'edit' => 'bank1']]);

        Route::prefix('bank-branch')->group(function () {
            $url = 'Admin\BankBranchesController';

            Route::get('/create', $url.'@create')->name('bank');
            Route::post('/', $url.'@store');
            Route::get('/edit/{bank_branch}', $url.'@edit')->where('bank_branch', '[0-9]+')->name('bank');
            Route::put('/{bank_branch}', $url.'@update')->where('bank_branch', '[0-9]+');
            Route::delete('/{bank_branch}', $url.'@destroy')->where('bank_branch', '[0-9]+');
        });

        Route::prefix('report-column')->group(function () {
            $url = 'Admin\ReportColumnsController';

            Route::get('/', $url.'@index')->name('reporttemplates');
            Route::put('/', $url.'@update');
        });

        Route::resource('report-template', 'Admin\ReportTemplatesController', ['names' => ['index' => 'reporttemplates', 'create' => 'reporttemplates', 'edit' => 'reporttemplates']]);
    
        Route::middleware('CheckConfidential')->group(function () {            
            Route::get('transaction/duplicate/{transaction}', 'Admin\TransactionsController@duplicate')->where('transaction', '[0-9]+');
        });
    });

    // Access Level 1, 2, and 3
    Route::middleware('checkRole:1|2|3')->group(function () {
        
        // Sequence

        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';

            Route::get('/create/{trans_type}/{trans_company}', $url.'@create')->where('trans_company', '[0-9]+')->name('transaction');
            Route::post('/create', $url.'@store');
            
            Route::middleware('CheckConfidential')->group(function () {
                $url = 'Admin\TransactionsController';
                
                Route::get('/edit/{transaction}', $url.'@edit')->where('transaction', '[0-9]+')->name('transaction');
                Route::put('/edit/{transaction}', $url.'@update')->where('transaction', '[0-9]+');
                Route::get('/view/{transaction}', $url.'@show')->where('transaction', '[0-9]+')->name('transaction');
                Route::get('/reset/{transaction}', $url.'@reset')->where('transaction', '[0-9]+');
                Route::put('/cancel/{transaction}', $url.'@cancel')->where('transaction', '[0-9]+');
                Route::put('/manage/{transaction}', $url.'@manage')->where('transaction', '[0-9]+');
                // Route::get('/report/', $url.'@report')->middleware('checkRole:1|2');
            });

            Route::put('/edit-company/', $url.'@update_company')->name('transaction');
            Route::get('/report-all/', $url.'@report_all')->name('transactionreport');
            Route::get('/toggle-visibility/{id}', $url.'@toggle_confidential')->name('transaction');

            Route::get('/{trans_page}/{trans_company?}', $url.'@index')->where('trans_company', '[0-9]+')->name('transaction');

            Route::post('/api-search', $url.'@api_search');
        });

        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';
            
            Route::get('/create', $url.'@create')->name('transaction');
            Route::post('/create', $url.'@store');

            Route::get('/create-reimbursement', $url.'@create_reimbursement')->name('transaction');
            Route::post('/create-reimbursement', $url.'@store_reimbursement');

            Route::middleware('CheckConfidential')->group(function () {
                $url = 'Admin\TransactionsFormsController';
                
                Route::get('/view/{transaction}', $url.'@show')->where('transaction', '[0-9]+')->name('transaction');
    
                Route::get('/edit/{transaction}', $url.'@edit')->where('transaction', '[0-9]+')->name('transaction');
                Route::put('/edit/{transaction}', $url.'@update')->where('transaction', '[0-9]+');
    
                Route::get('/edit-reimbursement/{transaction}', $url.'@edit_reimbursement')->where('transaction', '[0-9]+')->name('transaction');
                Route::put('/edit-reimbursement/{transaction}', $url.'@update_reimbursement')->where('transaction', '[0-9]+');
    
                Route::put('/edit-issued/{transaction}', $url.'@update_issued')->where('transaction', '[0-9]+');
                
                // if is_bank
                Route::put('/edit-issued-company/{transaction}', $url.'@update_issued_company')->where('transaction', '[0-9]+');
                Route::get('/edit-issued-clear/{transaction}', $url.'@update_issued_clear')->where('transaction', '[0-9]+');
    
                Route::get('/reset/{transaction}', $url.'@reset')->where('transaction', '[0-9]+');
                Route::put('/cancel/{transaction}', $url.'@cancel')->where('transaction', '[0-9]+');
                // Route::put('/approval/{transaction}', $url.'@approval')->where('transaction', '[0-9]+');
                Route::get('/approval/{transaction}', $url.'@approval')->where('transaction', '[0-9]+');
                Route::get('/print/{transaction}', $url.'@print')->where('transaction', '[0-9]+')->name('transaction');
                Route::put('/issue/{transaction}', $url.'@issue')->where('transaction', '[0-9]+');
            });

            // Route::get('/report/', $url.'@report')->middleware('checkRole:1|2');
            Route::get('/print-issued/', $url.'@print_issued')->middleware('checkRole:1|2');

            // Route::get('/{trans_page}/{trans_company?}', $url.'@index')->where('trans_company', '[0-9]+');
        });

        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';
            
            Route::get('/create', $url.'@create')->name('transaction');
            Route::post('/create', $url.'@store');

            Route::middleware('CheckConfidential')->group(function () {
                $url = 'Admin\TransactionsLiquidationController';
                
                Route::get('/view/{transaction}', $url.'@show')->where('transaction', '[0-9]+')->name('transaction');
                Route::get('/edit/{transaction}', $url.'@edit')->where('transaction', '[0-9]+')->name('transaction');
                Route::put('/edit/{transaction}', $url.'@update')->where('transaction', '[0-9]+');
                Route::get('/reset/{transaction}', $url.'@reset')->where('transaction', '[0-9]+');
                // Route::put('/approval/{transaction}', $url.'@approval')->where('transaction', '[0-9]+');
                Route::get('/approval/{transaction}', $url.'@approval')->where('transaction', '[0-9]+');
                Route::get('/print/{transaction}', $url.'@print')->where('transaction', '[0-9]+')->name('transaction');
                Route::post('/clear/{transaction}', $url.'@clear')->where('transaction', '[0-9]+');
                Route::put('/clear/{transaction}', $url.'@clear_edit')->where('transaction', '[0-9]+');
                
                Route::get('/finder-liquidation/{transaction}', $url.'@finder_liquidation')->where('transaction', '[0-9]+');
                Route::get('/finder-attachment/{transaction}', $url.'@finder_attachment')->where('transaction', '[0-9]+');
            });

            // Route::get('/report/', $url.'@report')->middleware('checkRole:1|2');
            Route::get('/report-deposit/', $url.'@report_deposit')->middleware('checkRole:1|2');
            Route::get('/print-cleared/', $url.'@print_cleared')->middleware('checkRole:1|2');
            

            // Route::get('/{trans_page}/{trans_company?}', $url.'@index')->where('trans_company', '[0-9]+');
        });

        // People

        Route::get('my-account', 'People\MyAccountController@index')->name('myaccount');
        Route::put('my-account', 'People\MyAccountController@update')->name('myaccount');
    });
});
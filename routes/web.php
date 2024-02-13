<?php
use Illuminate\Support\Facades\Mail;
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

Route::get('/mail',  function () {
    Mail::queue(new \App\Mail\NotificationsAlmostDueMail([
        'to' => 'kmbarsana@gmail.com',
        'name' => 'name',
        'url' => 'url',
        'project' => 'project',
        'no' => 'no',
        'purpose' => 'purpose',
        'amount' => 'amount',
    ]));
});

Auth::routes();

Route::group(['middleware' => ['auth', 'CheckUserAccess:active', 'CheckConfidential']], function () {
    
    Route::get('/', 'Main\ChooseAppController@index')->name('chooseapp');

    Route::get('/sequence-dashboard', 'Admin\DashboardController@index')->name('sequence-dashboard');
    Route::get('/people-dashboard', 'People\DashboardController@index')->name('people-dashboard');
    Route::get('/leaves-dashboard', 'Leaves\DashboardController@index')->name('leaves-dashboard');
    Route::get('/resources-dashboard', 'Resources\DashboardController@index')->name('resources-dashboard');
    Route::get('/travels-dashboard', 'Travels\DashboardController@index')->name('travels-dashboard');
    
    Route::get('/notifications/almost-due', 'Admin\NotificationsController@almost_due')->name('notifications-almost-due');
    Route::get('/notifications/past-due', 'Admin\NotificationsController@past_due')->name('notifications-past-due');
 
    Route::get('my-account', 'People\MyAccountController@index')->name('myaccount');
    Route::put('my-account', 'People\MyAccountController@update')->name('myaccount');
    
    // Seq Role
    Route::middleware('CheckUserAccess:seq_role')->group(function () {
        Route::resource('role', 'Admin\RolesController', ['names' => ['index' => 'role', 'create' => 'role', 'edit' => 'role']]);
    });

    // Seq Settings
    Route::middleware('CheckUserAccess:seq_settings')->group(function () {
        Route::prefix('settings')->group(function () {
            $url = 'Admin\SettingsController';

            Route::get('/', $url.'@index')->name('settings');
            Route::post('/', $url.'@update')->name('settings');
        });
    });
    
    // Seq Company
    Route::middleware('CheckUserAccess:seq_comp')->group(function () {
        Route::resource('company', 'Admin\CompanyController', ['names' => ['index' => 'company', 'create' => 'company', 'edit' => 'company']]);
    });

    // Seq Company Project
    Route::middleware('CheckUserAccess:seq_comp_proj')->group(function () {
        Route::prefix('company-project')->group(function () {
            $url = 'Admin\CompanyProjectsController';

            Route::get('/{company}', $url.'@index')->where('company', '[0-9]+')->name('company');
            Route::get('/{company}/create', $url.'@create')->where('company', '[0-9]+')->name('company');
            Route::get('/edit/{companyProject}', $url.'@edit')->where('companyProject', '[0-9]+')->name('company');
            Route::post('/{company}', $url.'@store')->where('company', '[0-9]+');
            Route::put('/{companyProject}', $url.'@update')->where('companyProject', '[0-9]+');
            Route::delete('/{companyProject}', $url.'@destroy')->where('companyProject', '[0-9]+');
        });   
    });

    // Seq Revert Status & Force Cancel & Force Renew
    Route::prefix('control-panel')->group(function() {        
        Route::middleware('CheckUserAccess:seq_rev_stat')->group(function () {
            $url = 'Admin\ControlPanelsController';
            Route::get('/revert-status', $url.'@revert_status')->name('revertstatus');
            Route::post('/revert-status', $url.'@revert_status_store');

            Route::get('/revert-status-prev', $url.'@revert_status_prev')->name('revertstatus');
            Route::post('/revert-status-prev', $url.'@revert_status_store_prev');
        });
        
        Route::middleware('CheckUserAccess:seq_force_cancel')->group(function () {
            $url = 'Admin\ControlPanelsController';
            Route::get('/force-cancel', $url.'@force_cancel')->name('forcecancel');
            Route::post('/force-cancel', $url.'@force_cancel_store');
        });

        Route::middleware('CheckUserAccess:seq_force_renew')->group(function () {
            $url = 'Admin\ControlPanelsController';
            Route::get('/force-renew', $url.'@force_renew')->name('forcerenew');
            Route::post('/force-renew', $url.'@force_renew_store');
        });
    });

    // Seq COA
    Route::middleware('CheckUserAccess:seq_coa')->group(function () {
        Route::resource('coa-tagging', 'Admin\CoaTaggingController', ['names' => ['index' => 'coatagging', 'create' => 'coatagging', 'edit' => 'coatagging']]);
    });
    
    // Seq Expense Type
    Route::middleware('CheckUserAccess:seq_expense')->group(function () {
        Route::resource('expense-type', 'Admin\ExpenseTypesController', ['names' => ['index' => 'expensetype', 'create' => 'expensetype', 'edit' => 'expensetype']]);
    });
    
    // Seq Particulars
    Route::middleware('CheckUserAccess:seq_particulars')->group(function () {
        Route::resource('particular', 'Admin\ParticularsController', ['names' => ['index' => 'particular', 'create' => 'particular', 'edit' => 'particular']]);
    });
    
    // Seq VAT
    Route::middleware('CheckUserAccess:seq_vat')->group(function () {
        Route::resource('vat-type', 'Admin\VatTypesController', ['names' => ['index' => 'vattype', 'create' => 'vattype', 'edit' => 'vattype']]);
    });
    
    // Seq Released By
    Route::middleware('CheckUserAccess:seq_rel_by')->group(function () {
        Route::resource('released-by', 'Admin\ReleasedByController', ['names' => ['index' => 'releasedby', 'create' => 'releasedby', 'edit' => 'releasedby']]);        
    });

    // Seq Cost Type
    Route::middleware('CheckUserAccess:seq_cost_type')->group(function () {
        Route::resource('cost-type', 'Admin\CostTypesController', ['names' => ['index' => 'costtype', 'create' => 'costtype', 'edit' => 'costtype']]);        
    });
    
    // Seq Bank / Bank Branch
    Route::middleware('CheckUserAccess:seq_bank')->group(function () {
        Route::resource('bank', 'Admin\BanksController', ['names' => ['index' => 'bank', 'create' => 'bank', 'edit' => 'bank'], 'asd' => ['index' => 'bank1', 'create' => 'bank1', 'edit' => 'bank1']]);
        Route::prefix('bank-branch')->group(function () {
            $url = 'Admin\BankBranchesController';
    
            Route::get('/create', $url.'@create')->name('bank');
            Route::post('/', $url.'@store');
            Route::get('/edit/{bank_branch}', $url.'@edit')->where('bank_branch', '[0-9]+')->name('bank');
            Route::put('/{bank_branch}', $url.'@update')->where('bank_branch', '[0-9]+');
            Route::delete('/{bank_branch}', $url.'@destroy')->where('bank_branch', '[0-9]+');
        });
    });

    // Seq Report Column
    Route::middleware('CheckUserAccess:seq_rep_col')->group(function () {
        Route::prefix('report-column')->group(function () {
            $url = 'Admin\ReportColumnsController';

            Route::get('/', $url.'@index')->name('reporttemplates');
            Route::put('/', $url.'@update');
        });    
    });
    
    // Seq Report Template
    Route::middleware('CheckUserAccess:seq_rep_temp')->group(function () {
        Route::resource('report-template', 'Admin\ReportTemplatesController', ['names' => ['index' => 'reporttemplates', 'create' => 'reporttemplates', 'edit' => 'reporttemplates']]);
    });
    
    // Peo User
    Route::middleware('CheckUserAccess:peo_user')->group(function () {
        Route::resource('user', 'People\UsersController', ['names' => ['index' => 'user', 'create' => 'user', 'edit' => 'user']]);
    });

    // Peo Announcement
    Route::middleware('CheckUserAccess:peo_announcement')->group(function () {
        Route::prefix('people-announcement')->group(function () {
            $url = 'People\AnnouncementController';

            Route::get('/', $url.'@index')->name('people-announcement');
            Route::post('/', $url.'@update')->name('people-announcement');
        });
    });
            
    // Peo Settings
    Route::middleware('CheckUserAccess:peo_settings')->group(function () {
        Route::prefix('people-settings')->group(function () {
            $url = 'People\SettingsController';

            Route::get('/', $url.'@index')->name('people-settings');
            Route::post('/', $url.'@update')->name('people-settings');
        });
    });

    // Peo Activity Log
    Route::middleware('CheckUserAccess:peo_activity')->group(function () {
        Route::prefix('activity-log')->group(function () {
            $url = 'People\ActivityLogsController';

            Route::get('/', $url.'@index')->name('activitylog');
        });

        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';

            Route::get('/finder-liquidation/{transaction}', $url.'@finder_liquidation')->where('transaction', '[0-9]+');
            Route::get('/finder-attachment/{transaction}', $url.'@finder_attachment')->where('transaction', '[0-9]+');
        });
    });
    
    // Peo Database
    Route::middleware('CheckUserAccess:peo_db')->group(function () {
        Route::get('/db-backups', 'People\DBBackupsController@db_backups')->name('dbbackups');
        Route::get('/db-backups-zip', 'People\DBBackupsController@db_backups_zip')->name('dbbackups');
        Route::get('/db-backups-generate', 'People\DBBackupsController@db_backups_generate')->name('dbbackups');
    });

    // Peo UA Routes
    Route::middleware('CheckUserAccess:peo_ua_route')->group(function () {
        Route::get('/ua-route', 'People\UaRoutesController@index')->name('uaroute');
        Route::put('/ua-route', 'People\UaRoutesController@update');
    });

    // Peo UA Levels
    Route::middleware('CheckUserAccess:peo_ua_level')->group(function () {
        Route::get('/ua-level', 'People\UaLevelsController@index')->name('ualevel');
        Route::put('/ua-level', 'People\UaLevelsController@update');
    });

    // Peo UA Level Route
    Route::middleware('CheckUserAccess:peo_ua_level_route')->group(function () {
        Route::get('/ua-level-route', 'People\UaLevelRoutesController@index')->name('ualevelroute');
        Route::put('/ua-level-route', 'People\UaLevelRoutesController@update');
    });

    // Resources FAQ
    Route::get('/faqs', 'Resources\FaqsController@index')->name('faq');
    Route::post('/faqs/api-search', 'Resources\FaqsController@api_search');
    
    Route::middleware('CheckUserAccess:res_faq_manage')->group(function () {
        Route::prefix('faqs-manage')->group(function () {
            $url = 'Resources\FaqsController';

            Route::get('/', $url.'@manage_index')->name('faqmanage');
            Route::get('/create', $url.'@create')->name('faqmanage');
            Route::post('/', $url.'@store');
            Route::get('/{faq}/edit', $url.'@edit')->where('faq', '[0-9]+')->name('faqmanage');
            Route::put('/{faq}', $url.'@update')->where('faq', '[0-9]+');
            Route::delete('/{faq}', $url.'@destroy')->where('faq', '[0-9]+');
        });
    });

    // Resources Forms
    Route::get('/forms', 'Resources\FormsController@index')->name('form');

    Route::middleware('CheckUserAccess:res_faq_manage')->group(function () {
        Route::prefix('forms-manage')->group(function () {
            $url = 'Resources\FormsController';

            Route::get('/', $url.'@manage_index')->name('formmanage');
            Route::get('/create', $url.'@create')->name('formmanage');
            Route::post('/', $url.'@store');
            Route::get('/{form}/edit', $url.'@edit')->where('form', '[0-9]+')->name('faqmanage');
            Route::put('/{form}', $url.'@update')->where('form', '[0-9]+');
            Route::delete('/{form}', $url.'@destroy')->where('form', '[0-9]+');
        });
    });

    // Resources Files
    Route::get('/files', 'Resources\FilesController@index')->name('file');

    Route::middleware('CheckUserAccess:res_file_manage')->group(function () {
        Route::prefix('files-manage')->group(function () {
            $url = 'Resources\FilesController';

            Route::get('/', $url.'@manage_index')->name('filemanage');
            Route::get('/create', $url.'@create')->name('filemanage');
            Route::post('/', $url.'@store');
            Route::get('/{file}/edit', $url.'@edit')->where('file', '[0-9]+')->name('filemanage');
            Route::put('/{file}', $url.'@update')->where('file', '[0-9]+');
            Route::delete('/{file}', $url.'@destroy')->where('file', '[0-9]+');
        });
    });

    // Travels Travels
    Route::middleware('CheckUserAccess:trv_travel')->group(function () {
        Route::prefix('travels')->group(function () {
            $url = 'Travels\TravelsController';

            Route::get('/', $url.'@index')->name('travel-travel');
            Route::get('/view/{travel}', $url.'@show')->name('travel-travel');
            Route::get('/create', $url.'@create')->name('travel-travel');
            Route::post('/', $url.'@store');
            Route::get('/{travel}/edit', $url.'@edit')->where('travel', '[0-9]+')->name('travel-travel');
            Route::put('/{travel}', $url.'@update')->where('travel', '[0-9]+');
            // Route::delete('/{travel}', $url.'@destroy')->where('travel', '[0-9]+');
            Route::put('/cancel/{travel}', $url.'@cancel')->where('travel', '[0-9]+');

            Route::get('/for-review/{travel}', $url.'@for_review')->where('travel', '[0-9]+');
            Route::get('/for-approval/{travel}', $url.'@for_approval')->where('travel', '[0-9]+');
            Route::put('/for-booking/{travel}', $url.'@for_booking')->where('travel', '[0-9]+');
            Route::put('/booked/{travel}', $url.'@booked')->where('travel', '[0-9]+');
        });
    });

    // Travels Request Types
    Route::middleware('CheckUserAccess:trv_request_type')->group(function () {
        Route::resource('travels-request-type', 'Travels\TravelsRequestTypesController', ['names' => ['index' => 'travel-request-type', 'create' => 'travel-request-type', 'edit' => 'travel-request-type']]);
        Route::prefix('travels-request-type-option')->group(function () {
            $url = 'Travels\TravelsRequestTypeOptionsController';
    
            Route::get('/create', $url.'@create')->name('travel-request-type');
            Route::post('/', $url.'@store');
            Route::get('/edit/{request_type_option}', $url.'@edit')->where('request_type_option', '[0-9]+')->name('travel-request-type');
            Route::put('/{request_type_option}', $url.'@update')->where('request_type_option', '[0-9]+');
            Route::delete('/{request_type_option}', $url.'@destroy')->where('request_type_option', '[0-9]+');
        });
    });

    // Travels Roles
    Route::middleware('CheckUserAccess:trv_role')->group(function () {
        Route::get('travels-role', 'Travels\TravelsRolesController@index')->name('travel-role');
        Route::put('travels-role', 'Travels\TravelsRolesController@update');
    });

    // Lea Settings
    Route::middleware('CheckUserAccess:lea_settings')->group(function () {
        Route::prefix('leaves-settings')->group(function () {
            $url = 'Leaves\SettingsController';

            Route::get('/', $url.'@index')->name('leaves-settings');
            Route::post('/', $url.'@update')->name('leaves-settings');
        });
    });

    // Lea Reasons
    Route::middleware('CheckUserAccess:lea_reason')->group(function () {
        Route::resource('leaves-reason', 'Leaves\ReasonsController', ['names' => ['index' => 'leavesreason', 'create' => 'leavesreason', 'edit' => 'leavesreason']]);
    });
    
    // Lea Department
    Route::middleware('CheckUserAccess:lea_dept')->group(function () {
        Route::resource('leaves-department', 'Leaves\DepartmentsController', ['names' => ['index' => 'leavesdepartment', 'create' => 'leavesdepartment', 'edit' => 'leavesdepartment']]);
        
        Route::prefix('leaves-department-user')->group(function () {
            $url = 'Leaves\DepartmentUsersController';
    
            Route::get('/create/{department}', $url.'@create')->where('department', '[0-9]+')->name('leavesdepartment');
            Route::post('/', $url.'@store');
            Route::get('/edit/{department_user}', $url.'@edit')->where('department_user', '[0-9]+')->name('leavesdepartment');
            Route::put('/{department_user}', $url.'@update')->where('department_user', '[0-9]+');
            Route::delete('/{department_user}', $url.'@destroy')->where('department_user', '[0-9]+');
        });
    });
        
    // Lea Adjustment
    Route::middleware('CheckUserAccess:lea_adjust')->group(function () {
        Route::resource('leaves-adjustment', 'Leaves\AdjustmentsController', ['names' => ['index' => 'leavesadjustment', 'create' => 'leavesadjustment', 'edit' => 'leavesadjustment'], 'except' => ['show']]);
    });

    // Lea Department Peak
    Route::middleware('CheckUserAccess:lea_dept_peak')->group(function () {
        Route::prefix('leaves-department-peak')->group(function () {
            $url = 'Leaves\DepartmentPeaksController';

            Route::get('/{department}', $url.'@index')->where('department', '[0-9]+')->name('leavesdepartment');
            Route::get('/{department}/create', $url.'@create')->where('department', '[0-9]+')->name('leavesdepartment');
            Route::post('/{department}', $url.'@store')->where('department', '[0-9]+');
            Route::get('/edit/{departmentpeak}', $url.'@edit')->where('departmentpeak', '[0-9]+')->name('leavesdepartment');
            Route::put('/{departmentpeak}', $url.'@update')->where('departmentpeak', '[0-9]+');
            Route::delete('/{departmentpeak}', $url.'@destroy')->where('departmentpeak', '[0-9]+');
        });
    });

    // Lea Department Peak (My)
    Route::middleware('CheckUserAccess:lea_dept_peak_my')->group(function () {
        Route::prefix('leaves-department-peak/my')->group(function () {
            $url = 'Leaves\DepartmentPeaksController';

            Route::get('/', $url.'@index_my')->name('leavespeakmy');
            Route::get('/{department}/create', $url.'@create_my')->where('department', '[0-9]+')->name('leavespeakmy');
            Route::post('/{department}', $url.'@store_my')->where('department', '[0-9]+');
            Route::get('/edit/{departmentpeak}', $url.'@edit_my')->where('departmentpeak', '[0-9]+')->name('leavespeakmy');
            Route::put('/{departmentpeak}', $url.'@update_my')->where('departmentpeak', '[0-9]+');
            Route::delete('/{departmentpeak}', $url.'@destroy_my')->where('departmentpeak', '[0-9]+');
        });
    });

    // Lea Department (My)
    Route::middleware('CheckUserAccess:lea_dept_my')->group(function () {
        Route::prefix('leaves-department/my')->group(function () {
            $url = 'Leaves\DepartmentsController';

            Route::get('/{leavesDepartment}', $url.'@index_my')->name('leavesdepartmentmy');
        });
    });
    
    // Lea Adjustment (My)
    Route::middleware('CheckUserAccess:lea_adjust_my')->group(function () {
        Route::get('leaves-adjustment/my', 'Leaves\AdjustmentsController@index_my')->name('leavesadjustmentmy');
    });

    // Seq Transaction Create
    Route::middleware('CheckUserAccess:trans_add')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';
            
            Route::get('/create/{trans_type}/{trans_company}', $url.'@create')->where('trans_company', '[0-9]+')->name('transaction');
            Route::post('/create', $url.'@store');
        });
    });

    // Seq Transaction Duplicate
    Route::middleware('CheckUserAccess:trans_dup')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';
            
            Route::get('/duplicate/{transaction}', $url.'@duplicate')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Edit
    Route::middleware('CheckUserAccess:trans_edit')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';

            Route::get('/edit/{transaction}', $url.'@edit')->where('transaction', '[0-9]+')->name('transaction');
            Route::put('/edit/{transaction}', $url.'@update')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Edit
    Route::middleware('CheckUserAccess:trans_cancel')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';

            Route::put('/cancel/{transaction}', $url.'@cancel')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Edit
    Route::middleware('CheckUserAccess:trans_manage')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';

            Route::put('/manage/{transaction}', $url.'@manage')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Toggle Confidential
    Route::middleware('CheckUserAccess:trans_toggle_conf')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';

            Route::get('/toggle-visibility/{id}', $url.'@toggle_confidential')->name('transaction');
        });
    });

    // Seq Transaction Toggle Confidential Own
    Route::middleware('CheckUserAccess:trans_toggle_conf_own')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';

            Route::get('/toggle-visibility-own/{id}', $url.'@toggle_confidential_own')->name('transaction');
        });
    });

    // Seq Transaction Form Create
    Route::middleware('CheckUserAccess:form_add')->group(function () {
        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';

            Route::get('/create', $url.'@create')->name('transaction');
            Route::post('/create', $url.'@store');

            Route::get('/create-reimbursement', $url.'@create_reimbursement')->name('transaction');
            Route::post('/create-reimbursement', $url.'@store_reimbursement');
        });
    });

    // Seq Transaction Form Edit
    Route::middleware('CheckUserAccess:form_edit')->group(function () {
        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';

            Route::get('/edit/{transaction}', $url.'@edit')->where('transaction', '[0-9]+')->name('transaction');
            Route::put('/edit/{transaction}', $url.'@update')->where('transaction', '[0-9]+');

            Route::get('/edit-reimbursement/{transaction}', $url.'@edit_reimbursement')->where('transaction', '[0-9]+')->name('transaction');
            Route::put('/edit-reimbursement/{transaction}', $url.'@update_reimbursement')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Form Cancel
    Route::middleware('CheckUserAccess:form_cancel')->group(function () {
        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';

            Route::put('/cancel/{transaction}', $url.'@cancel')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Form Approval
    Route::middleware('CheckUserAccess:form_approval')->group(function () {
        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';

            Route::get('/approval/{transaction}', $url.'@approval')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Form Issue
    Route::middleware('CheckUserAccess:form_issue')->group(function () {
        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';

            Route::put('/issue/{transaction}', $url.'@issue')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Form Edit Issued
    Route::middleware('CheckUserAccess:form_edit_issued')->group(function () {
        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';

            Route::put('/edit-issued/{transaction}', $url.'@update_issued')->where('transaction', '[0-9]+');
            
            // if is_bank
            Route::put('/edit-issued-company/{transaction}', $url.'@update_issued_company')->where('transaction', '[0-9]+');
            Route::get('/edit-issued-clear/{transaction}', $url.'@update_issued_clear')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Form Print
    Route::middleware('CheckUserAccess:form_print')->group(function () {
        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';

            Route::get('/print/{transaction}', $url.'@print')->where('transaction', '[0-9]+')->name('transaction');
        });
    });    

    // Seq Transaction Liq Create
    Route::middleware('CheckUserAccess:liq_add')->group(function () {
        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';

            Route::get('/create', $url.'@create')->name('transaction');
            Route::post('/create', $url.'@store');
        });
    });

    // Seq Transaction Liq Edit
    Route::middleware('CheckUserAccess:liq_edit')->group(function () {
        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';

            Route::get('/edit/{transaction}', $url.'@edit')->where('transaction', '[0-9]+')->name('transaction');
            Route::put('/edit/{transaction}', $url.'@update')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Liq Approval
    Route::middleware('CheckUserAccess:liq_approval')->group(function () {
        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';

            Route::get('/approval/{transaction}', $url.'@approval')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Liq Clear
    Route::middleware('CheckUserAccess:liq_clear')->group(function () {
        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';

            Route::post('/clear/{transaction}', $url.'@clear')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Liq Edit Cleared
    Route::middleware('CheckUserAccess:liq_edit_cleared')->group(function () {
        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';

            Route::put('/clear/{transaction}', $url.'@clear_edit')->where('transaction', '[0-9]+');
        });
    });

    // Seq Transaction Liq Print
    Route::middleware('CheckUserAccess:liq_print')->group(function () {
        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';

            Route::get('/print/{transaction}', $url.'@print')->where('transaction', '[0-9]+')->name('transaction');
        });
    });

    // Seq Transaction Reset Edit Count
    Route::middleware('CheckUserAccess:trans_reset')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';
            Route::get('/reset/{transaction}', $url.'@reset')->where('transaction', '[0-9]+');
        });
    
        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';
            Route::get('/reset/{transaction}', $url.'@reset')->where('transaction', '[0-9]+');
        });
    
        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';
            Route::get('/reset/{transaction}', $url.'@reset')->where('transaction', '[0-9]+');
        });
    });    

    // Seq Transaction Reports
    Route::middleware('CheckUserAccess:trans_report')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';
            Route::get('/report-all/', $url.'@report_all')->name('transactionreport');
        });
        
        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';
            Route::get('/print-issued/', $url.'@print_issued');
        });
    
        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';
            Route::get('/report-deposit/', $url.'@report_deposit');
            Route::get('/print-cleared/', $url.'@print_cleared');
        });

        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';
            Route::get('/report-projects/', $url.'@report_projects')->name('transactionreportproject');
        });
    });    
    
    // Seq Transaction View
    Route::middleware('CheckUserAccess:trans_view')->group(function () {
        Route::prefix('transaction')->group(function () {
            $url = 'Admin\TransactionsController';

            Route::get('/print-cancelled/{transaction}', $url.'@print_cancelled')->where('transaction', '[0-9]+')->name('transaction');
            Route::get('/view/{transaction}', $url.'@show')->where('transaction', '[0-9]+')->name('transaction');
            Route::get('/{trans_page}/{trans_company?}', $url.'@index')->where('trans_company', '[0-9]+')->name('transaction');
            
            Route::post('/api-search', $url.'@api_search');
            
            Route::put('/note/{transaction}', $url.'@note')->where('transaction', '[0-9]+');
            Route::put('/edit_note/{transaction}/{transaction_note}', $url.'@edit_note')->where('transaction_note', '[0-9]+');
            Route::get('/delete_note/{transaction}/{transaction_note}', $url.'@destroy_note')->where('transaction_note', '[0-9]+');
            
            Route::put('/edit-company/', $url.'@update_company')->name('transaction');
            
        });

        Route::prefix('transaction-form')->group(function () {
            $url = 'Admin\TransactionsFormsController';

            Route::get('/view/{transaction}', $url.'@show')->where('transaction', '[0-9]+')->name('transaction');
        });

        Route::prefix('transaction-liquidation')->group(function () {
            $url = 'Admin\TransactionsLiquidationController';

            Route::get('/view/{transaction}', $url.'@show')->where('transaction', '[0-9]+')->name('transaction');
        });
    });
});
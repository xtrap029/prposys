<?php

namespace App\Helpers;
use App\Settings;

final class BreadHelper {
    public static function get($class, $action, $special = null) {
        $bread = [];

        switch ($class) {
            case 'dashboard': break;
            case 'activitylog': $bread[] = ['activity log', '/activity-log']; break;
            case 'dbbackups': $bread[] = ['database', '/control-panel/db-backups']; break;
            case 'revertstatus': $bread[] = ['revert status', '/control-panel/revert-status']; break;
            case 'forcecancel': $bread[] = ['force cancel', '/control-panel/force-cancel']; break;
            case 'coatagging': $bread[] = ['category / class', '/coa-tagging']; break;
            case 'expensetype': $bread[] = ['expense type', '/expense-type']; break;
            case 'particular': $bread[] = ['particulars', '/particular']; break;
            case 'vattype': $bread[] = ['tax type', '/vat-type']; break;
            case 'releasedby': $bread[] = ['released by', '/released-by']; break;
            case 'reporttemplates': $bread[] = ['report template', '/report-template']; break;
            case 'myaccount': $bread[] = ['my account', '/my-account']; break;
            case 'transactionreport': $bread[] = ['reports', '#']; break;
            case 'leavesreason': $bread[] = ['leaves reason', '/leaves-reason']; break;
            case 'leavesdepartment': $bread[] = ['leaves department', '/leaves-department']; break;
            case 'leavespeakmy': $bread[] = ['leaves peak', '/leaves-department-peak/my']; break;
            case 'leavesdepartmentmy': $bread[] = ['leaves department', '#']; break;
            case 'leavesadjustment': $bread[] = ['leaves adjustment', '#']; break;
            case 'ualevel': $bread[] = ['level', '#']; break;
            case 'uaroute': $bread[] = ['route', '#']; break;
            case 'ualevelroute': $bread[] = ['level route', '#']; break;
            case 'transaction': 
                $bread[] = ['transactions', '/transaction/prpo/'.$special]; 
                break;
            default: $bread[] = [$class, '/'.$class]; break;
        }

        switch ($action) {
            case 'index':
            case 'report_all':
            case 'revert_status':
            case 'force_cancel':
            case 'print_issued':
            case 'print_cleared':
            case 'index_my':
            case 'db_backups': break;
            case 'create_my': $bread[] = ['create', '#']; break;
            case 'edit_my': $bread[] = ['edit', '#']; break;
            case 'create_reimbursement': $bread[] = ['create', '#']; break;
            case 'edit_reimbursement': $bread[] = ['edit', '#']; break;
            default: $bread[] = [$action, '#']; break;
        }

        return $bread;
    }

    public function footer_label() {
        return Settings::where('type', 'FOOTER_LABEL')->first()->value;
    }
}

?>
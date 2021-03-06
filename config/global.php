<?php 
    return [
        'admin_subadmin' => [1, 2],
        'generated' => [1],
        'form_generated' => [1, 5],
        'cancelled' => [3],
        'generated_form' => [5],
        'form_approval' => [6],
        'forms' => [5, 6],
        'form_issued' => [4],
        'form_approval_printing' => [4, 6],
        'liquidation_generated' => [7],
        'liquidation_approval' => [8],
        'liquidations' => [7, 8],
        'liquidation_cleared' => [9],
        'page_generated' => [1, 3],
        'page_form' => [5, 6, 4],
        'page_liquidation' => [7, 8, 9],
        'approver_form' => [1, 2],
        'approver_liquidation' => [1, 2],
        'unliquidated' => [1, 5, 6, 4, 7, 8],
        'deposit_type' => ['CHECK', 'ONLINE', 'CASH'],
        'issued_cleared' => [4, 9],
        'status' => [1, 3, 4, 5, 6, 7, 8, 9],
        'status_approval' => [6, 8],
        'currency' => ['PHP', 'USD', 'EUR'],
        'currency_label' => ['PHP', 'USD', 'EUR'],
        'trans_types' => ['pr', 'po', 'pc'],
        'site_icon' => '/images/logo.png',
        'site_banner' => '/images/banners/'.rand(1, 32).'.png',
        'control_types' => ['Cash', 'Check', 'Online'],
        'control_types_pc' => 'Petty Cash',
        'trans_category' => ['reg', 'dt', 'bp', 'hr', 'rb', 'bt'],
        'trans_category_column' => ['', 'is_deposit', 'is_bills', 'is_hr', 'is_reimbursement', 'is_bank'],
        'trans_category_column_2' => ['is_reg', 'is_deposit', 'is_bills', 'is_hr', 'is_reimbursement', 'is_bank'],
        'trans_category_label' => ['Regular Transaction', 'Deposit Transaction', 'Bills Payment', 'Human Resource', 'Reimbursement', 'Fund Transfer'],
        'trans_category_label_filter' => ['All Categories', 'Deposit Transaction', 'Bills Payment', 'Human Resource', 'Reimbursement', 'Fund Transfer'],
        'trans_category_label_filter_2' => ['All', 'Deposit Transaction', 'Bills Payment', 'Human Resource', 'Reimbursement', 'Fund Transfer'],
        'trans_category_label_make_form' => ['Make Form', 'Make Form', 'Make Form', 'Make Form', 'Reimbursement', 'Fund Transfer'],
        'trans_category_label_edit_form' => ['Edit Form', 'Edit Form', 'Edit Form', 'Edit Form', 'Edit Reimbursement', 'Edit Fund Transfer'],
        'trans_category_label_create_liq' => ['Liquidate', 'Liquidate', 'Liquidate', 'Liquidate', 'Reimburse', 'Deposit Form'],
        'trans_category_label_liq_print' => ['Liquidation', 'Liquidation', 'Liquidation', 'Liquidation', 'Reimbursement', 'Deposit Form'],
        'status_filter' => [
            ['Generated', '1,5'],
            ['For Approval', '6'],
            ['Issued', '4'],
            ['Liq./For Approval', '7,8'],
            ['Cleared', '9'],
            ['Cancelled', '3'],
        ],
        'status_filter_reports' => [
            ['Generated', '1,5'],
            ['For Approval', '6'],
            ['Issued', '4'],
            ['Liq./Generated', '7'],
            ['Liq./For Approval', '8'],
            ['Cleared', '9'],
            ['Cancelled', '3'],
        ],
        'attachment_format' => ['jpg', 'jpeg', 'png', 'pdf'],
    ];
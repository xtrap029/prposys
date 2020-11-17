<?php 
    return [
        'admin_subadmin' => [1, 2],
        'generated' => [1],
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
        'deposit_type' => ['BANK', 'CHECK', 'ONLINE'],
        'issued_cleared' => [4, 9],
        'status' => [1, 3, 4, 5, 6, 7, 8, 9],
        'status_approval' => [6, 8],
        'trans_types' => ['pr', 'po', 'pc'],
        'site_icon' => '/images/logo.png',
        'site_banner' => '/images/banners/'.rand(1, 32).'.png',
        'control_types' => ['Cash', 'Online', 'Check'],
        'control_types_pc' => 'Petty Cash',
        'trans_category' => ['reg', 'dt', 'bp', 'hr'],
        'trans_category_label' => ['Regular Transaction', 'Deposit Transaction', 'Bills Payment', 'Human Resource'],
        'status_filter' => [
            ['PR/PO# Generated', '1,5'],
            ['Form-Approval', '6'],
            ['Issued', '4'],
            ['Liq-Approval', '7,8'],
            ['Cleared', '9'],
            ['Cancelled', '3'],
        ]
    ];
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ReportColumn;
use Illuminate\Http\Request;

class ReportColumnsController extends Controller {

    public function index() {
        return view('pages.admin.reportcolumn.index')->with([
            'report_columns' => ReportColumn::orderBy('label', 'asc')->get()
        ]);
    }
}

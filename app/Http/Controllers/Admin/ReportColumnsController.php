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

    public function update(Request $request) {
        $data = $request->validate([
            'label_2.*' => ['required'],
            'description_2.*' => ['required'],
            'id.*' => ['required', 'exists:report_columns,id'],
        ]);

        foreach ($data['id'] as $key => $id) {
            $column = ReportColumn::find($id)->update([
                'label_2' => $data['label_2'][$key],
                'description_2' => $data['description_2'][$key],
                'updated_id' => auth()->id()
            ]);
        }

        return redirect('/report-column')->with('success', 'Report Template'.__('messages.edit_success'));
    }
}

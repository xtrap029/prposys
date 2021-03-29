<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ReportTemplate;
use App\ReportColumn;
use App\ReportTemplatesColumn;
use Illuminate\Http\Request;

class ReportTemplatesController extends Controller {

    public function index() {
        return view('pages.admin.reporttemplate.index')->with([
            'report_templates' => ReportTemplate::orderBy('name', 'asc')->get()
        ]);
    }

    public function create() {
        $columns = ReportColumn::orderBy('label', 'asc')->get();
        return view('pages.admin.reporttemplate.create')->with([
            'columns' => $columns
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required'],
            'column_label.*' => ['required'],
            'column_id.*' => ['required', 'exists:report_columns,id'],
        ]);

        $template = [];
        $template['name'] = $data['name'];
        $template['owner_id'] = auth()->id();
        $template['updated_id'] = auth()->id();
        $report_template = ReportTemplate::create($template);

        $columns = [];
        foreach ($data['column_id'] as $key => $value) {
            $columns['report_template_id'] = $report_template->id;
            $columns['report_column_id'] = $value;
            $columns['label'] = $data['column_label'][$key];
            $columns['owner_id'] = auth()->id();
            ReportTemplatesColumn::create($columns);
        }

        return redirect('/report-template')->with('success', 'Report Template'.__('messages.create_success'));
    }

    public function edit(ReportTemplate $report_template) {
        $columns = ReportColumn::orderBy('label', 'asc')->get();
        $selected_columns = [];

        foreach ($report_template->templatecolumn as $key => $value) {
            $selected_columns[] = $value->report_column_id;
        }
        
        return view('pages.admin.reporttemplate.edit')->with([
            'report_template' => $report_template,
            'selected_columns' => $selected_columns,
            'columns' => $columns
        ]);
    }

    public function update(Request $request, ReportTemplate $report_template) {
        $data = $request->validate([
            'name' => ['required'],
            'column_label.*' => ['required'],
            'column_id.*' => ['required', 'exists:report_columns,id'],
        ]);

        $template = [];
        $template['name'] = $data['name'];
        $template['updated_id'] = auth()->id();
        $report_template->update($template);

        ReportTemplatesColumn::where('report_template_id', $report_template->id)->delete();
        $columns = [];
        foreach ($data['column_id'] as $key => $value) {
            $columns['report_template_id'] = $report_template->id;
            $columns['report_column_id'] = $value;
            $columns['label'] = $data['column_label'][$key];
            $columns['owner_id'] = auth()->id();
            ReportTemplatesColumn::create($columns);
        }

        return redirect('/report-template')->with('success', 'Report Template'.__('messages.edit_success'));
    }

    public function destroy(ReportTemplate $report_template) {
        $report_template->updated_id = auth()->id();
        $report_template->save();
        $report_template->delete();

        return redirect('/report-template')->with('success', 'Report Template'.__('messages.delete_success'));
    }
}

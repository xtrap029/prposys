<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ReportTemplatesColumn extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
        'label',
        'createdby.name'
    ];
    protected static $logName = 'Report Templates Columns';
    protected static $logOnlyDirty = true;

    public function createdby() {
        return $this->belongsTo(User::class, 'created_id');
    }

    public function template() {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function column() {
        return $this->belongsTo(ReportColumn::class, 'report_column_id');
    }
}

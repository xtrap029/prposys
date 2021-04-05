<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ReportColumn extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
        'label',
        'label_2',
        'description',
        'description_2',
        'updatedby.name'
    ];
    protected static $logName = 'Report Columns';
    protected static $logOnlyDirty = true;

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }
}

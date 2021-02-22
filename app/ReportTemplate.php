<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ReportTemplate extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
        'name',
        'updatedby.name'
    ];
    protected static $logName = 'Report Templates';
    protected static $logOnlyDirty = true;

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }

    public function templatecolumn() {
        return $this->hasMany(ReportTemplatesColumn::class);
    }
}

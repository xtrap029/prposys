<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class DepartmentsPeak extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
        'department.name',
        'date_from',
        'date_to',
        'remarks',
        'owner.name',
        'updatedby.name'
    ];
    protected static $logName = 'Department Peak';
    protected static $logOnlyDirty = true;

    public function department() {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }
}

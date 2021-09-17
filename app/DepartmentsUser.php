<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class DepartmentsUser extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['department.name', 'user.email', 'is_approver'];
    protected static $logName = 'Department User';
    protected static $logOnlyDirty = true;

    public function department() {
        return $this->belongsTo(Department::class)->withTrashed();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

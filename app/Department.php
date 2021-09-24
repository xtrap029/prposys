<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name'];
    protected static $logName = 'Department';
    protected static $logOnlyDirty = true;

    public function departmentsusers() {
        return $this->hasMany(DepartmentsUser::class);
    }

    public function departmentusermember() {
        return $this->hasMany(DepartmentsUser::class)->where('is_approver', '=', 0);
    }

    public function departmentuserapprover() {
        return $this->hasMany(DepartmentsUser::class)->where('is_approver', '=', 1);
    }

    public function departmentspeaks() {
        return $this->hasMany(DepartmentsPeak::class)->where('date_from', '>=', now())->orderBy('date_from', 'asc');
    }

    public function departmentspeaksdue() {
        return $this->hasMany(DepartmentsPeak::class)->where('date_from', '<', now())->orderBy('date_from', 'asc');
    }
}

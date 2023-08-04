<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CostType extends Model {
    use SoftDeletes;
    protected $table = 'cost_types';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
        'control_no',
        'name',
        'description'
    ];
    protected static $logName = 'Cost Type';
    protected static $logOnlyDirty = true;
}
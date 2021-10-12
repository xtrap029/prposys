<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LeaveAdjustment extends Model {
    use SoftDeletes;
    protected $table = 'leaves_adjustments';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['user.name',
                                        'quantity',
                                        'remarks',
                                        'owner.name',
                                        'updatedby.name'
                                        ];
    protected static $logName = 'Leave Adjustment';
    protected static $logOnlyDirty = true;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UaLevelRoute extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
        'uaroute.name',
        'ualevel.name',
        'uarouteoption.name',
        'owner.name',
        'updatedby.name',
    ];
    protected static $logName = 'UA Level Route';
    protected static $logOnlyDirty = true;

    public function uaroute() {
        return $this->belongsTo(UaRoute::class, 'ua_route_id');
    }

    public function ualevel() {
        return $this->belongsTo(UaLevel::class, 'ua_level_id');
    }

    public function uarouteoption() {
        return $this->belongsTo(UaRouteOption::class, 'ua_route_option_id');
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UaRouteOption extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name'];
    protected static $logName = 'UA Route Option';
    protected static $logOnlyDirty = true;

    public function levelroutes() {
        return $this->hasMany(UaLevelRoute::class);
    }
}
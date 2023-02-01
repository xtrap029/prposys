<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TravelsRequestType extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name'];
    protected static $logName = 'Travels Request Type';
    protected static $logOnlyDirty = true;

    public function travelsrequesttypeoptions() {
        return $this->hasMany(TravelsRequestTypeOption::class)->orderBy('name');
    }
}
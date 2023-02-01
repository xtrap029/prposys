<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TravelsRequestTypeOption extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name', 'travelrequesttype.name'];
    protected static $logName = 'Travel Request Type Option';
    protected static $logOnlyDirty = true;

    public function travelrequesttype() {
        return $this->belongsTo(TravelsRequestType::class);
    }
}

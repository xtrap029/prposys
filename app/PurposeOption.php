<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PurposeOption extends Model {
    use SoftDeletes;
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['code', 'name'];
    protected static $logName = 'Purpose Option';
    protected static $logOnlyDirty = true;
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class File extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name', 'description', 'drive', 'ua_level_ids'];
    protected static $logName = 'File';
    protected static $logOnlyDirty = true;
}
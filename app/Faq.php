<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Faq extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['title', 'description', 'category'];
    protected static $logName = 'FAQ';
    protected static $logOnlyDirty = true;
}
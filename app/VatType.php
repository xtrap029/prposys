<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class VatType extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['code', 'name', 'vat', 'wht', 'is_pr', 'is_pc', 'is_pc'];
    protected static $logName = 'Vat Type';
    protected static $logOnlyDirty = true;
}

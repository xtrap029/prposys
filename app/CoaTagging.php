<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CoaTagging extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    
    use LogsActivity;
    protected static $logAttributes = ['name', 'notes', 'company.name'];
    protected static $logName = 'Category/Class';
    protected static $logOnlyDirty = true;

    public function company() {
        return $this->belongsTo(Company::class);
    }
}

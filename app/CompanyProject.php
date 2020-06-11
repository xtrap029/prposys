<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CompanyProject extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    
    use LogsActivity;
    protected static $logAttributes = ['project', 'company.name'];
    protected static $logName = 'Company Project';

    public function company() {
        return $this->belongsTo(Company::class);
    }
}

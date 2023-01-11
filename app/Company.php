<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Company extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['code', 'name', 'logo'];
    protected static $logName = 'Company';
    protected static $logOnlyDirty = true;

    public function coataggings() {
        return $this->hasMany(CoaTagging::class)->orderBy('name');
    }

    public function companyProject() {
        return $this->hasMany(CompanyProject::class)->orderBy('project');
    }
}

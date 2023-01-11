<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Travel extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $table = 'travels';

    use LogsActivity;
    protected static $logAttributes = ['name_id', 'date_from', 'date_to', 'company_project_id', 'destination', 'traveling_users', 'traveling_users_static'];
    protected static $logName = 'Travel';
    protected static $logOnlyDirty = true;

    public function name() {
        return $this->belongsTo(User::class, 'name_id');
    }

    public function companyProject() {
        return $this->belongsTo(CompanyProject::class, 'company_project_id');
    }

    public function attachments() {
        return $this->hasMany(TravelsAttachment::class);
    }
}
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
    protected static $logAttributes = ['date_from', 'date_to', 'company_project_id', 'destination', 'traveling_users_static'];
    protected static $logName = 'Travel';
    protected static $logOnlyDirty = true;

    public function companyProject() {
        return $this->belongsTo(CompanyProject::class, 'company_project_id');
    }

    public function attachments() {
        return $this->hasMany(TravelsAttachment::class);
    }

    public function passengers() {
        return $this->hasMany(TravelsPassenger::class);
    }

    public function requestType() {
        return $this->belongsTo(TravelsRequestType::class, 'travels_request_type_id');
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UaLevelConfidential extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
        'ualevel.name',
        'viewerualevel.name',
        'owner.name',
        'updatedby.name',
    ];
    protected static $logName = 'UA Level Confidential';
    protected static $logOnlyDirty = true;

    public function ualevel() {
        return $this->belongsTo(UaLevel::class, 'ua_level_id');
    }

    public function viewerualevel() {
        return $this->belongsTo(UaLevel::class, 'viewer_ua_level_id');
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }
}

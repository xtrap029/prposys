<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TravelsAttachment extends Model {
    use SoftDeletes;
    protected $table = 'travels_attachments';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['travel.id',
                                        'description',
                                        'file'
                                        ];
    protected static $logName = 'Travel Attachments';
    protected static $logOnlyDirty = true;

    public function travel() {
        return $this->belongsTo(Travel::class, 'travel_id');
    }
}

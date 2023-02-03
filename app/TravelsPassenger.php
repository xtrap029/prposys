<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TravelsPassenger extends Model {
    use SoftDeletes;
    protected $table = 'travels_passengers';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['travel.id',
                                        'user.name',
                                        'travel_no'
                                        ];
    protected static $logName = 'Travel Passengers';
    protected static $logOnlyDirty = true;

    public function travel() {
        return $this->belongsTo(Travel::class, 'travel_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

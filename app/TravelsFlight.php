<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TravelsFlight extends Model {
    use SoftDeletes;
    protected $table = 'travels_flights';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['travel.id',
                                        'name',
                                        'remarks',
                                        'time_in',
                                        'time_out',
                                        'fee',
                                        'fee_car',
                                        'fee_baggage',
                                        'fee_land',
                                        'is_selected',
                                        ];
    protected static $logName = 'Travel Flights';
    protected static $logOnlyDirty = true;

    public function travel() {
        return $this->belongsTo(Travel::class, 'travel_id');
    }
}

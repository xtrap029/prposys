<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TravelsHotel extends Model {
    use SoftDeletes;
    protected $table = 'travels_hotels';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['travel.id',
                                        'name',
                                        'remarks',
                                        'fee',
                                        'fee_car',
                                        'fee_land',
                                        'is_selected',
                                        ];
    protected static $logName = 'Travel Hotels';
    protected static $logOnlyDirty = true;

    public function travel() {
        return $this->belongsTo(Travel::class, 'travel_id');
    }
}

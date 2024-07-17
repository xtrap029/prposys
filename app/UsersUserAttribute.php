<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class UsersUserAttribute extends Model {
    use SoftDeletes;
    protected $table = 'users_user_attributes';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['user.name',
                                        'user_attribute.name',
                                        ];
    protected static $logName = 'Users User Attribute';
    protected static $logOnlyDirty = true;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user_attribute() {
        return $this->belongsTo(UserAttribute::class, 'user_attribute_id');
    }
}

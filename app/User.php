<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'avatar', 'LIMIT_UNLIQUIDATEDPR_AMOUNT', 'LIMIT_UNLIQUIDATEDPR_COUNT',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    use LogsActivity;
    protected static $logAttributes = ['name', 'email', 'avatar', 'role.name', 'LIMIT_UNLIQUIDATEDPR_AMOUNT', 'LIMIT_UNLIQUIDATEDPR_COUNT'];
    protected static $logName = 'User';
    protected static $logOnlyDirty = true;

    public function role() {
        return $this->belongsTo(Role::class);
    }
}

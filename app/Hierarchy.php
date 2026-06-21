<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Hierarchy extends Model
{
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['user_id', 'parent_id', 'company_id'];
    protected static $logName = 'Hierarchy';
    protected static $logOnlyDirty = true;

    public function company()
    {
        return $this->belongsTo(\App\Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        // parent_id stores the parent's user_id (not hierarchy.id)
        return $this->hasMany(Hierarchy::class, 'parent_id', 'user_id');
    }
}

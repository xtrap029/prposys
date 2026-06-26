<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassTypeCompanyApprover extends Model {
    protected $guarded = [];

    public function classtype() {
        return $this->belongsTo(ClassType::class, 'class_type_id');
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

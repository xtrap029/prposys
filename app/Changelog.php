<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    protected $fillable = ['version', 'release_date', 'type', 'description'];

    protected $casts = ['release_date' => 'date'];
}

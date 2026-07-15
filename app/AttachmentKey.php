<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttachmentKey extends Model
{
    protected $fillable = ['transaction_id', 'attachment_type', 'key', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}

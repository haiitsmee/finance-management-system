<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityLog extends Model
{
    public $timestamps = true;
    protected static function booted()
    {

    }

    protected $fillable = [
        'user_id',
        'action',
        'activity',
        'ip_address'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

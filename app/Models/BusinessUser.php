<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessUser extends Model
{
    protected $table = 'business_users';
    protected $keyType = 'int';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'business_id',
        'user_id',
    ];

    public function business(): BelongsTo {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

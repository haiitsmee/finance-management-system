<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// app/Models/Debt.php
class Debt extends Model
{
    use HasFactory;
     protected $fillable = [
        'business_id',
        'category',
        'supplier',
        'amount',
        'due_date',
        'date',
        'is_paid',
        'description',
        'document_path',
    ];

    public function business(): BelongsTo {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
}


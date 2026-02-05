<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $keyType = 'int';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'business_id',
        'transaction_category_id',
        'product',
        'price',
        'product_quantity',
        'total',
        'note',
        'transaction_date',
        'description',
        'status',
        'image',
        'repayment_document_path',
    ];

    public function transactionCategory(): BelongsTo {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id', 'id');
    }

    public function business(): BelongsTo {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
}

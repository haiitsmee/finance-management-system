<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionCategory extends Model
{
    protected $table = 'transaction_categories';
    protected $keyType = 'int';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'tags'
    ];

    public function transactions(): HasMany {
        return $this->hasMany(Transaction::class);
    }
}

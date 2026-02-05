<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CSRIncome extends Model
{

    protected $table = 'csr_incomes';

    protected $fillable = [
        'type', 
        'business_id', 
        'source', 
        'amount', 
        'date', 
        'is_withdrawn', 
        'document_path', 
        'description'
    ];    
        public function business()
    {
        return $this->belongsTo(Business::class);
    }
    
    public function scopeNotWithdrawn($query)
    {
        return $query->where('is_withdrawn', false);
    }
    
    public function scopeWithdrawn($query)
    {
        return $query->where('is_withdrawn', true);
    }
}

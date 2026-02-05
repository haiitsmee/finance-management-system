<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CSRDistribution extends Model
{
        protected $table = 'csr_distributions';

    protected $fillable = [
        'activity', 
        'purpose', 
        'amount', 
        'date', 
        'document_path',
        'description'
    ];
    }

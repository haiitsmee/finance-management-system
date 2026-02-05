<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CSRWithdrawal extends Model
{
        protected $table = 'csr_withdrawals';

    protected $fillable = ['amount', 'date', 'purpose', 'description'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CSRSetting extends Model
{
    protected $table = 'csr_settings';
    protected $fillable = ['percentage'];
}

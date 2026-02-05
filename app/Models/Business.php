<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $table = 'businesses';
    protected $keyType = 'int';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'slug',
        'responsible',
        'contact_responsible',
        'employee_headcount',
        'description',
        'is_visible'
    ];

    public function getLetterheadData()
    {
        $letterheadNames = [
            'tralis-dan-baja-kontruksi' => 'SEKAR TRALISINDO',
            'makanan-dan-minuman' => 'SATRIA FOOD', 
            'furniture-dan-interior' => 'SEKAR SATRIA FURNITURE',
            'transportasi-dan-alat-berat' => 'SEKAR KENCONO',
            'tour-dan-travel' => 'SATRIA TOUR & TRAVEL',
            'pusat' => 'SATRIA GROUP'
        ];

        return [
            'division' => $this->name, 
            'name' => $letterheadNames[$this->slug] ?? 'SATRIA GROUP'
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'business_users', 'business_id', 'user_id');
    }

    public function transactions(): HasMany {
        return $this->hasMany(Transaction::class);
    }

    public function debts(): HasMany {
        return $this->hasMany(Debt::class);
    }
}

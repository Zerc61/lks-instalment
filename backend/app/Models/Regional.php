<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Regional extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'province',
        'district'
    ];

    public function societies() {
        return $this->hasMany(Society::class);
    }
}

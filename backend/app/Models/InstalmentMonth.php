<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstalmentMonth extends Model
{
    use HasFactory;

    protected $fillable = [
        'instalment_car',
        'month',
        'description',
    ];

    public function instalmentCar() {
        return $this->belongsTo(InstalmentCar::class);
    }
}

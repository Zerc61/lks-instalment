<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'instalment_car_id',
        'months',
        'nominal',
        'notes',
        'apply_status'
    ];

    public function society() {
        return $this->belongsTo(Society::class);
    }

    public function instalmentCar() {
        return $this->belongsTo(InstalmentCar::class);
    }
}

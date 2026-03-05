<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstalmentCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'car',
        'brand',
        'price',
        'description',
    ];

    public function instalmentMonths() {
        return $this->hasMany(InstalmentMonth::class);
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }
}

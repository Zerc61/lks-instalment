<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Society extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'regional_id',
        'name',
        'id_card_number',
        'password',
        'role',
        'born_date',
        'gender',
        'address'
    ];

    protected $hidden = [
        'password'
    ];

    public function regional() {
        return $this->belongsTo(Regional::class);
    }

    public function validation() {
        return $this->hasOne(Validation::class);
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }
}

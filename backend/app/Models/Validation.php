<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Validation extends Model
{
    use HasFactory;

    protected $fillable = [
        'society_id',
        'job',
        'job_description',
        'income',
        'reason_accepted',
        'status',
        'validator_notes',
    ];

    public function society() {
        return $this->belongsTo(Society::class);
    }
}

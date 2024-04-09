<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'number_phone',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}

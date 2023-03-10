<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $table = "absensis";
    protected $fillable = [
        'nim', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nim', 'nim');
    }
}

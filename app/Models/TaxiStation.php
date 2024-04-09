<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxiStation extends Model
{
    use HasFactory;


 public $fillable = [
        'name',
        'latitude',
        'longitude',
        'status',
        'city',
        'address',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }



}

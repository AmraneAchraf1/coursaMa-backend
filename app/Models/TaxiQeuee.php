<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxiQeuee extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'taxi_number',
        'enter_time',
        'exit_time',
        'from',
        'to',
        'passengers',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }



}

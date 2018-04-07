<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'description', 'car_plates', 'road_name', 'posted_at', 'latitude', 'longitude', 'user_id', 'occurred_at'
    ];
    protected $dates = ['posted_at', 'occurred_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

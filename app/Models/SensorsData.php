<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorsData extends Model
{
    /** @use HasFactory<\Database\Factories\SensorDataFactory> */
    use HasFactory;

    // protected $fillable = ['sensor_id', 'temperature', 'humidity', 'recorded_at'];

    protected $guarded = [];

}

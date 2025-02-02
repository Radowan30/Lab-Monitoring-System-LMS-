<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    /** @use HasFactory<\Database\Factories\SensorFactory> */
    use HasFactory;

    protected $primaryKey = 'sensor_id'; // Set the primary key column
    protected $fillable = ['lab_room_name', 'temp_threshold', 'humidity_threshold']; // Mass-assignable fields
}

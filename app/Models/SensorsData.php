<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorsData extends Model
{
    use HasFactory;

    protected $table = 'sensors_data'; // This should match your database table name
    
    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }
}

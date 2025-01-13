<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'customer_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'passport_number',
        'institution',
        'specific_institution',
        'position',
        'phone_number',
        'email',
        'entry_datetime',
        'exit_datetime',
        'purpose_of_usage',
        'purpose_description',
        'equipment_used',
        'type_of_analysis',
        'supervisor_name',
        'usage_duration',
        'suggestions',
        'technical_issues'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'entry_datetime' => 'datetime',
        'exit_datetime' => 'datetime',
        'usage_duration' => 'float'
    ];
}
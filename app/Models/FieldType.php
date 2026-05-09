<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    protected $fillable = [
        'name',
        'type',
        'icon',
        'default_settings',
        'default_validation',
        'default_styles',
    ];

    protected $casts = [
        'default_settings'   => 'array',
        'default_validation' => 'array',
        'default_styles'     => 'array',
    ];
}

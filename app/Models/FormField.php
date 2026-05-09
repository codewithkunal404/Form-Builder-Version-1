<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
     protected $fillable = [
        'form_id',
        'field_type_id',
        'label',
        'name',
        'order',
        'settings',
        'validation',
        'styles',
    ];

    protected $casts = [
        'settings'   => 'array',
        'validation' => 'array',
        'styles'     => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function fieldType()
    {
        return $this->belongsTo(FieldType::class);
    }
}

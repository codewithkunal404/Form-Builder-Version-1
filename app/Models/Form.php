<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'framework',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('order');
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}

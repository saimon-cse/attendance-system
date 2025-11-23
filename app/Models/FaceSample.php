<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceSample extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'facial_landmarks' => 'array',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
        'has_mask' => 'boolean',
        'has_glasses' => 'boolean',
        'has_beard' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

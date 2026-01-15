<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureFlagModel extends Model
{
    protected $table = 'feature_flags';
    
    protected $fillable = [
        'key',
        'enabled',
        'description',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
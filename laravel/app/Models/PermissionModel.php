<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    protected $table = 'permissions';
    
    protected $fillable = [
        'name',
        'description',
    ];

}
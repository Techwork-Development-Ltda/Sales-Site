<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRoleModel extends Model
{
    protected $table = 'permission_role';
    
    protected $fillable = [
        'role_id',
        'permission_id'
    ];

    protected $primaryKey = 'role_id';
    public $incrementing = false;
    public $timestamps = false;

}
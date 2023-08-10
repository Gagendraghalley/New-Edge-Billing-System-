<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class UsersModel extends Model
{
    //For generating UUID
    use HasFactory, Uuid;
    public $timestamps = false;
    protected $table = "users";
    protected $fillable = [
        'id',
        'email',
        'password',
        'name',
        'phone_number',
        'org_id',
        // 'company',
        'position',
        'system_role',
        'profile_path',
        'created_at',
        'created_by',
        'Status'
    ];
}

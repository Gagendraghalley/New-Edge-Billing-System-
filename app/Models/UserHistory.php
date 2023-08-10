<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class UserHistory extends Model
{
    //For generating UUID
    use HasFactory, Uuid;
    public $timestamps = false;
    protected $table = "users_history";
    protected $fillable = [
        'id',
        'parent_id',
        'email',
        'password',
        'name',
        'phone_number',
        'org_id',
        'position',
        'system_role',
        'profile_path',
        'created_at',
        'created_by',
        'Status'
    ];
}

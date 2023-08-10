<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Task_details_history extends Model
{
       //For generating UUID
        use HasFactory, Uuid;
        public $timestamps = false;
        protected $table = "task_history";
        protected $fillable = [
        'id',
        'parent_id',
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'taskStatus',
        'userId',
        'ownership',
        'created_at',
        'created_by',
    ];
}

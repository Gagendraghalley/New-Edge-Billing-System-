<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class ApplicationSequence extends Model
{
    use HasFactory, Uuid;
    public $timestamps = false;
    protected $table="application_sequence";
    protected $fillable = [
        'id','service_name','last_sequence'
    ];
}

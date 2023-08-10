<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Organization extends Model
{
     //For generating UUID
    use HasFactory, Uuid;
    public $timestamps = false;
    protected $table = "organization_details";
    protected $fillable = [
            'id',
            'name',
            'description',
            'org_code',
            'status',
            'created_at',
            'created_by',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class PurchaseDetails extends Model
{
    use HasFactory, Uuid;
    public $timestamps = false;
    protected $table="purchase_details";
    protected $fillable = [
            'id',
            'description',
            'purchaseId',
            'qty',
            'rate',
            'part_number',
            'duration',
            'amount',
            'created_by',
            'created_at',
    ];
}

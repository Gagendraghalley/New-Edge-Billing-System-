<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Purchase extends Model
{
     //For generating UUID
    use HasFactory, Uuid;
    public $timestamps = false;
        protected $table = "purchase";
        protected $fillable = [
            'id' ,               
            'order_date',
            'unit',
            'end_user',
            'addressTo',
            'termAndCondition',
            'totalAmount',
            'totalAmountInWords',
            'refer_no',
            'received_date',
            'receivedBy_id',
            'status',
            'purchase_file',
            'academic_year',
            'userId',
            'created_at',
            'created_by',
];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Invoice extends Model
{
       //For generating UUID
        use HasFactory, Uuid;
        public $timestamps = false;
        protected $table = "invoice";
        protected $fillable = [
            'id' ,               
            'entry_date',
            'due_date',
            'received_date',
            'reference_number',
            'addressTo',
            'workorderNo',
            'totalAmount',
            'totalAmountInWords',
            'Tpn_no',
            'Bill_no',
            'status',
            'bill_file',
            'academic_year',
            'userId',
            'reference_byId',
            'created_at',
            'created_by',
];
}

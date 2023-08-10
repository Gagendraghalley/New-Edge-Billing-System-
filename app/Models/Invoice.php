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
            'addressTo',
            'workorderNo',
            'totalAmount',
            'totalAmountInWords',
            'Tpn_no',
            'Bill_no',
            'academic_year',
            'userId',
            'created_at',
            'created_by',
];
}

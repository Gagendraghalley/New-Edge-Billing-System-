<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class InvoiceDetails extends Model
{
    use HasFactory, Uuid;
    public $timestamps = false;
    protected $table="Invoice_details";
    protected $fillable = [
            'id',
            'particular',
            'invoiceId',
            'qty',
            'rate',
            'amount',
            'created_by',
            'created_at',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'required',
        'number',
        'document',
        'requisition',
        'iscovered',
        'order_id',
        'code_smaterial',
        'document_5',
        'document_6',
        'document_7',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'office', 'invoice', 'invoice_number', 'client', 'credit', 'status_id', 'delete'
    ];
    
    public static function countAll(){
        $list = DB::select(DB::raw("SELECT COUNT(*) AS tot FROM orders"));

        return $list[0]->tot;
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function partials()
    {
        return $this->hasMany(Partial::class);
    }

    public function follow()
    {
        return $this->hasOne(Follow::class);
    }

    public function cancelation()
    {
        return $this->hasOne(Cancelation::class);
    }

    public function rebilling()
    {
        return $this->hasOne(Rebilling::class);
    }

    public function debolution()
    {
        return $this->hasOne(Debolution::class);
    }

    public function purchaseorder()
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    public function manufacturingorder()
    {
        return $this->hasOne(ManufacturingOrder::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}

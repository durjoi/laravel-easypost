<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $guarded = [];

    public function item()
    {
        return $this->hasMany(\App\Models\Admin\OrderItem::class, 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }

    public function shipment()
    {
        return $this->hasOne(\App\Models\Admin\Shipment::class, 'order_id');
    }
}

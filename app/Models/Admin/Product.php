<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    protected $guarded = [];
    protected $appedns = ['dimension'];

    public function brand()
    {
        return $this->belongsTo(\App\Models\Admin\Brand::class, 'brand_id');
    }

    public function photos()
    {
        return $this->hasMany(\App\Models\Admin\ProductPhoto::class, 'product_id');
    }

    public function photo()
    {
        return $this->hasOne(\App\Models\Admin\ProductPhoto::class, 'product_id');
    }

    public function getDimensionAttribute()
    {
        if($this->attributes['height'] && $this->attributes['width'] && $this->attributes['length']){
            return $this->attributes['height'].' in X '.$this->attributes['width'].' in X '.$this->attributes['length'].' in';
        }
        return '';
    }
}

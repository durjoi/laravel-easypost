<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\HashidsManager;

class Product extends Model
{
    protected $table = "products";
    protected $guarded = [];
    protected $appends = ['dimension', 'hashedid'];
    // protected $appedns = ['dimension'];

    public function brand()
    {
        return $this->belongsTo(\App\Models\Admin\SettingsBrand::class, 'brand_id');
    }

    public function photos()
    {
        return $this->hasMany(\App\Models\Admin\ProductPhoto::class, 'product_id');
    }

    public function photo()
    {
        return $this->hasOne(\App\Models\Admin\ProductPhoto::class, 'product_id');
    }

    public function networks()
    {
        return $this->hasMany(\App\Models\Admin\ProductNetwork::class, 'product_id');
    }

    public function storages()
    {
        return $this->hasMany(\App\Models\Admin\ProductStorage::class, 'product_id');
    }

    public function categories()
    {
        return $this->hasMany(\App\Models\Admin\ProductCategory::class, 'product_id');
    }

    public function storagesForBuying()
    {
        return $this->storages()->where('amount','=', null);
    }


    
    // // results in a "problem", se examples below
    // public function available_videos() {
    //     return $this->videos()->where('available','=', 1);
    // }
    public function getDimensionAttribute()
    {
        if($this->attributes['height'] && $this->attributes['width'] && $this->attributes['length']){
            return $this->attributes['height'].' in X '.$this->attributes['width'].' in X '.$this->attributes['length'].' in';
        }
        return '';
    }

    public function getHashedidAttribute()
    {
        return \Hashids::encode($this->id);
    }
}

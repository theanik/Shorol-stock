<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }
    public function supplier(){
        return $this->belongsTo('App\Supplier','supplier_id');
    }
    public function sales(){
        return $this->hasMany(Sales::class,'purchases_id');
    }

}

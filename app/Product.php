<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function supplier(){
        return $this->belongsTo('App\Supplier');
    }
    public function purchases(){
        return $this->hasOne('App\Purchases');
    }
}

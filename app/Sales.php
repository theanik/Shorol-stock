<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }
    public function purchase(){
        return $this->belongsTo('App\Purchase','purchases_id');
    }
    public function customer(){
        return $this->belongsTo('App\Customer','customer_id');
    }
}

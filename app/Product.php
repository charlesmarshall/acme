<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //table name & key
    protected $table = "product";
    protected $primaryKey = "id";
    public $timestamps = true;
    
    public function baskets()
    {
        return $this->belongsToMany('App\Basket')
                    ->withTimestamps()
                    ->withPivot('id', 'unit_price', 'discount', 'code')
                    ;
    }
}

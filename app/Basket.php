<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    //table name & key
    protected $table = "basket";
    protected $primaryKey = "id";
    public $timestamps = true;


    public function products()
    {
        return $this->belongsToMany('App\Product')
                    ->withTimestamps()
                    ->withPivot('id', 'unit_price', 'discount', 'code')
                    ;
    }

    /**
     * Use array of product codes passed in, find product and add to this basket
     * Return and updated basket with discounts re-calculated
     */
    public function addByCode($codes)
    {
        foreach($codes as $code)
        {
            $item = \App\Product::where('code', $code)->first();
            if($item && $item->code == $code){
                $this->products()->save($item, [ 'unit_price' => $item->price, 'code' => $item->code ]);
            }
        }
        return $this->updateBasketWithDiscounts();
    }

    /**
     * Removing products based on array of codes and remove from the join table
     */
    public function removeByCode($codes)
    {
        
        foreach($codes as $code)
        {
            $item = \DB::table('basket_product')
                            ->where('basket_id', $this->id)
                            ->where('code', $code)
                            ->first();
            if($item && $item->code == $code){
                \DB::table('basket_product')->where('id', $item->id)->delete();
            }
        }
        return $this->updateBasketWithDiscounts();
    }
    
    
    /**
     * Currently, this only supports two for one and should be developed
     * into a more detailed solution to allow better expansion
     * Returns an updated version of the basket model
     */
    public function updateBasketWithDiscounts()
    {   
        // reset discounts
        \DB::table('basket_product')
                        ->where('basket_id', $this->id)
                        ->update(['discount' => 0]);
        // group by product code
        $grouped = [];        
        $basket = Basket::where("id", $this->id)->first();
        
        // only check two for one offers
        foreach($basket->products->where('twoForOne', true)->all() as $product){
            if(!isset($grouped[$product->code])) $grouped[$product->code] = [];
            $grouped[$product->code][] = $product;
        }
        
        // add discount to every second
        foreach($grouped as $code => $products)
        {
            $loop = 0;
            foreach($products as $prod){
                // controls who discount is added
                $addDiscount = ($loop % 2) == 1;
                if($addDiscount){
                    $prod->pivot->discount = ($prod->pivot->unit_price / 2);
                    $prod->pivot->save();
                }
                $loop ++;
            }
        }
        return Basket::where("id", $this->id)->first();

    }


    /**
     * returns a basket that can be used from the command line app
     * - returns the first basket matching the id, 
     * - or first in the db
     */
    public function forConsole($basketId = null)
    {
        if($basketId && ($found = \App\Basket::find($basketId))) return $found;
        else return \App\Basket::all()->first();
    }


    /**
     * Raw costs of each item added together
     */
    public function subTotal($products = null)
    {
        $value = 0;
        $products = $products ?? $this->products;
        foreach($products as $product)
        {
            $value = $value + $product->pivot->unit_price;
        }
        return $value;
    }

    /**
     * Work out total value of the basket
     */
    public function total()
    {
        $products = $this->products;
        $sub = $this->subTotal($products);
        $discount = $this->discount($products);
        $pre = ($sub + $discount);
        //delivery cost is based on un discounted value?
        $delivery = $this->delivery($pre);
        return  $pre + $delivery;
    }

    /**
     * 
     */
    public function discount($products = null)
    {
        $value = 0;
        $products = $products ?? $this->products;
        foreach($products as $product)
        {
            $value = $value + $product->pivot->discount;
        }

        return (0 - $value);
    }

    /**
     * Delivery costs
     * In a production app the logic in here should be generated from 
     * a database set of values
     */
    public function delivery($value = null)
    {
        $value = $value ?? $this->subTotal();

        if($value > 90) return 0;
        else if ($value > 50) return 2.95;
        else if ($value > 0 ) return 4.95;
        else return 0;
        
    }
}

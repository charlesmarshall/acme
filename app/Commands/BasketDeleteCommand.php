<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class BasketDeleteCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'basket:delete 
                            {code : the product code of the item to add to basket (required)}
                            {--basket= : (optional) basket id to use, if not found, reverts to new basket}
                            '
                            ;

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Remote an item to a basket';

    /**
     * Add product to basket and if the option is set, output the totals
     *
     * @return mixed
     */
    public function handle()
    {
        $productCodes = explode(",", $this->argument("code"));
    
        // get the basket
        $options = $this->options();
        if(isset($options['basket']) && !$options['basket']) throw new \Exception("Basket must have a value when set");

        // add to the basket
        $basket = new \App\Basket();
        $basket = $basket->forConsole($options['basket']);
        $basket = $basket->removeByCode($productCodes);
        
        $this->info("Basket [" . $basket->id . "] contains " . $basket->products->count() . ' products');
        // output items in basket
        foreach($basket->products as $product) {
            $this->info("  [" . $product->code . "] " . $product->name . " = " . $product->pivot->unit_price . " [". $product->pivot->discount."]");
        }
        // just a straight multiplication of costs
        $this->info("Sub Total: " . $basket->subTotal());
        $this->info("Discount: " . $basket->discount());
        $this->info("Delivery: " . $basket->delivery());
        $this->info("Total: " . $basket->total());
    }

    
}

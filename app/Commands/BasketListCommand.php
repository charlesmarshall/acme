<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class BasketListCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'basket:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List all baskets and their contents';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $baskets = \App\Basket::all();
        
        $this->info("Found " . $baskets->count() . " baskets.");
        // all baskets
        foreach($baskets as $basket)
        {
            $products = $basket->products;
            // all products in each basket
            $this->info("Basket [" . $basket->id . "] contains " . $products->count() . ' products');
            foreach( $products as $product)
            {
                $this->info("  [" . $product->code . "] " . $product->name . " = " . $product->pivot->unit_price . " [". $product->pivot->discount."]");
            }
            
        }
        
    }

}

<?php

namespace Tests\Unit;

use Tests\TestCase;

class BasketTest extends TestCase
{
    /**
     * Test the conditions in the test
     *
     * @return void
     */
    public function testBlueAndGreen()
    {
        $basket = new \App\Basket();
        $basket->save();

        $basket->addByCode( ["B01", "G01"] );

        $total = $basket->total();
        $this->assertEquals(37.85, $total);

        // tear down
        $basket->removeByCode(["B01", "G01"]);        
        $basket->delete();

    }

    public function testRedAndRed()
    {
        $basket = new \App\Basket();
        $basket->save();

        $basket = $basket->addByCode( ["R01", "R01"] );
        $total = $basket->total();
        $this->assertEquals(54.37, $total);

        // tear down
        $basket->removeByCode(["R01", "R01"]);        
        $basket->delete();

    }

    public function testRedAndGreen()
    {
        $basket = new \App\Basket();
        $basket->save();

        $basket = $basket->addByCode( ["R01", "G01"] );
        $total = $basket->total();
        $this->assertEquals(60.85, $total);

        // tear down
        $basket->removeByCode(["R01", "G01"]);        
        $basket->delete();
    }

    public function testBlueBlueRedRedAndRed()
    {
        $basket = new \App\Basket();
        $basket->save();

        $basket = $basket->addByCode( ["B01", "B01", "R01", "R01", "R01"] );
        $total = $basket->total();
        $this->assertEquals(98.27, $total);

        // tear down
        $basket->removeByCode(["B01", "B01", "R01", "R01", "R01"]);        
        $basket->delete();
    }
}

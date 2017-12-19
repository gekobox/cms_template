<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SupplierTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testSupplierActions()
    {
        $this->browse(function (Browser $browser) {
            //create supplier
            $browser->visit('/#/suppliers')
                    ->click('.btn-large')
                    ->type('#naam','new test supplier')
                    ->type('#notes','this is a note for the test supplier')
                    ->clickLink('Opslaan');
            $browser->pause(1000);
            $browser->assertSee('Zoeken');
            
            //modify supplier
            $browser->clickLink('new test supplier')
                    ->type('#naam','new test supplier modified')
                    ->type('#notes','this is a note for the test supplier modified')
                    ->clickLink('Opslaan');
            $browser->pause(1000);
            $browser->assertSee('Zoeken');
            
            //delete supplier
            $browser->check('.hs-table [type="checkbox"] + label')                    
                    ->clickLink('verwijderen');
            $browser->pause(1000);
            $browser->assertSee('Zoeken');
                    
        });
    }
}

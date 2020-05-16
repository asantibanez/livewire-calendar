<?php

namespace Asantibanez\LivewireCalendar\Tests;

use Orchestra\Testbench\TestCase;
use Asantibanez\LivewireCalendar\LivewireCalendarServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LivewireCalendarServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}

<?php

namespace CaiqueBispo\LivewireCalendar\Tests;

use CaiqueBispo\LivewireCalendar\LivewireCalendar;
use Livewire\LivewireManager;
use Livewire\Testing\TestableLivewire;

class LivewireCalendarTest extends TestCase
{
    private function createComponent($parameters = []): TestableLivewire
    {
        return app(LivewireManager::class)->test(LivewireCalendar::class, $parameters);
    }

    /** @test */
    public function can_build_component()
    {
        //Arrange

        //Act
        $component = $this->createComponent([]);

        //Assert
        $this->assertNotNull($component);
    }

    /** @test */
    public function can_navigate_to_next_month()
    {
        //Arrange
        $component = $this->createComponent([]);

        //Act
        $component->runAction('goToNextMonth');

        //Assert
        $this->assertEquals(
            today()->startOfMonth()->addMonthNoOverflow(),
            $component->get('startsAt')
        );

        $this->assertEquals(
            today()->endOfMonth()->startOfDay()->addMonthNoOverflow(),
            $component->get('endsAt')
        );
    }

    /** @test */
    public function can_navigate_to_previous_month()
    {
        //Arrange
        $component = $this->createComponent([]);

        //Act
        $component->runAction('goToPreviousMonth');

        //Assert
        $this->assertEquals(
            today()->startOfMonth()->subMonthNoOverflow(),
            $component->get('startsAt')
        );

        $this->assertEquals(
            today()->endOfMonth()->startOfDay()->subMonthNoOverflow(),
            $component->get('endsAt')
        );
    }

    /** @test */
    public function can_navigate_to_current_month()
    {
        //Arrange
        $component = $this->createComponent([]);

        $component->runAction('goToPreviousMonth');
        $component->runAction('goToPreviousMonth');
        $component->runAction('goToPreviousMonth');

        //Act
        $component->runAction('goToCurrentMonth');

        //Assert
        $this->assertEquals(
            today()->startOfMonth(),
            $component->get('startsAt')
        );

        $this->assertEquals(
            today()->endOfMonth()->startOfDay(),
            $component->get('endsAt')
        );
    }
}

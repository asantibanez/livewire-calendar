<?php

namespace Asantibanez\LivewireCalendar;

use Illuminate\Support\ServiceProvider;

class LivewireCalendarServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-calendar');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}

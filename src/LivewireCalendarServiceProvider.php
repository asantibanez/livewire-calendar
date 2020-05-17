<?php

namespace Asantibanez\LivewireCalendar;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LivewireCalendarServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-calendar');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/livewire-calendar'),
            ], 'livewire-calendar');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        Blade::directive('livewireCalendarScripts', function () {
            return <<<'HTML'
            <script>
                function onLivewireCalendarEventDragStart(event, eventId) {
                    event.dataTransfer.setData('id', eventId);
                }

                function onLivewireCalendarEventDragEnter(event, component, dateString) {
                    event.stopPropagation();
                    event.preventDefault();

                    let element = document.getElementById(`${component.id}-${dateString}`);
                    element.className = element.className + ` ${component.data.dragAndDropClasses} `;
                }

                function onLivewireCalendarEventDragLeave(event, component, dateString) {
                    event.stopPropagation();
                    event.preventDefault();

                    let element = document.getElementById(`${component.id}-${dateString}`);
                    element.className = element.className.replace(component.data.dragAndDropClasses, '');
                }

                function onLivewireCalendarEventDragOver(event) {
                    event.stopPropagation();
                    event.preventDefault();
                }

                function onLivewireCalendarEventDrop(event, component, dateString, year, month, day) {
                    event.stopPropagation();
                    event.preventDefault();

                    let element = document.getElementById(`${component.id}-${dateString}`);
                    element.className = element.className.replace(component.data.dragAndDropClasses, '');

                    const eventId = event.dataTransfer.getData('id');
                    component.call('onEventDropped', eventId, year, month, day);
                }
            </script>
HTML;
        });
    }
}

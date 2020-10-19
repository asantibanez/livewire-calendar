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

        Blade::directive('livewireCalendarScripts', function () {
            return <<<'HTML'
            <script>
                function onLivewireCalendarEventDragStart(event, eventId) {
                    event.dataTransfer.setData('id', eventId);
                }

                function onLivewireCalendarEventDragEnter(event, componentId, dateString, dragAndDropClasses) {
                    event.stopPropagation();
                    event.preventDefault();

                    let element = document.getElementById(`${componentId}-${dateString}`);
                    element.className = element.className + ` ${dragAndDropClasses} `;
                }

                function onLivewireCalendarEventDragLeave(event, componentId, dateString, dragAndDropClasses) {
                    event.stopPropagation();
                    event.preventDefault();

                    let element = document.getElementById(`${componentId}-${dateString}`);
                    element.className = element.className.replace(dragAndDropClasses, '');
                }

                function onLivewireCalendarEventDragOver(event) {
                    event.stopPropagation();
                    event.preventDefault();
                }

                function onLivewireCalendarEventDrop(event, componentId, dateString, year, month, day, dragAndDropClasses) {
                    event.stopPropagation();
                    event.preventDefault();

                    let element = document.getElementById(`${componentId}-${dateString}`);
                    element.className = element.className.replace(dragAndDropClasses, '');

                    const eventId = event.dataTransfer.getData('id');

                    window.Livewire.find(componentId).call('onEventDropped', eventId, year, month, day);
                }
            </script>
HTML;
        });
    }
}

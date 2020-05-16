<div
    class="w-full h-full p-2 {{ $dayInMonth ? $isToday ? 'bg-yellow-100' : ' bg-white ' : 'bg-gray-100' }} flex flex-col"
    wire:click="onDayClick({{ $day->year }}, {{ $day->month }}, {{ $day->day }})">

    <div class="flex items-center">
        <p class="text-sm {{ $dayInMonth ? ' font-medium ' : '' }}">
            {{ $day->format('j') }}
        </p>
        <p class="text-xs text-gray-600 ml-4">
            @if($events->isNotEmpty())
                {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
            @endif
        </p>
    </div>

    <div class="p-2 my-2 flex-1 overflow-y-scroll">
        <div class="grid grid-cols-1 grid-flow-row gap-2">
            @foreach($events as $event)
                <div
                    draggable="true"
                    ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')">
                    @include($eventView, [
                        'event' => $event,
                    ])
                </div>
            @endforeach
        </div>
    </div>

</div>

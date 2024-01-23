<div class="p-6 bg-gray-200 border border-gray-500 rounded-lg shadow dark:bg-gray-800"
    @if ($pollMillis !== null && $pollAction !== null) wire:poll.{{ $pollMillis }}ms="{{ $pollAction }}"
    @elseif($pollMillis !== null)
        wire:poll.{{ $pollMillis }}ms @endif>
    <div class="flex justify-between items-center mb-4">
        <button wire:click="goToPreviousMonth" class="bg-brown py-1.5 px-3 rounded-md text-white" style="background-color: blue">Previous Month</button>
        <div class="font-bold text-2xl">
            <span>{{ $startsAt->format('F Y') }}</span>
        </div>
        <button wire:click="goToNextMonth" class="bg-brown py-1.5 px-3 rounded-md text-white" style="background-color: blue">Next Month</button>
    </div>
    <div>
        @includeIf($beforeCalendarView)
    </div>

    <div class="">

        <div class="w-full flex flex-row rounded-lg " style="background-color: blue; ">
            @foreach ($monthGrid->first() as $day)
                @include($dayOfWeekView, ['day' => $day])
            @endforeach
        </div>

        @foreach ($monthGrid as $week)
            <div class="w-full flex flex-row ">
                @foreach ($week as $day)
                    @include($dayView, [
                        'componentId' => $componentId,
                        'day' => $day,
                        'dayInMonth' => $day->isSameMonth($startsAt),
                        'isToday' => $day->isToday(),
                        'events' => $getEventsForDay($day, $events),
                    ])
                @endforeach
            </div>
        @endforeach
    </div>

    <div>
        @includeIf($afterCalendarView)
    </div>
</div>

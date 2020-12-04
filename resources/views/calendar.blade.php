<div
    @if($pollMillis !== null && $pollAction !== null)
        wire:poll.{{ $pollMillis }}ms="{{ $pollAction }}"
    @elseif($pollMillis !== null)
        wire:poll.{{ $pollMillis }}ms
    @endif
>
    <div>
        @includeIf($beforeCalendarView)
    </div>

    <div class="flex">
        <div class="overflow-x-auto w-full">
            <div class="flex justify-start items-center space-x-4 my-6">
                <div class="cursor-pointer" wire:click.prevent="goToPreviousMonth()"><< Previous</div>
                <div class="font-semibold text-gray-700 uppercase">{{ $startsAt->copy()->format('F') }}</div>
                <div class="cursor-pointer" wire:click.prevent="goToNextMonth()">Next >></div>
            </div>

            <div class="inline-block min-w-full overflow-hidden">

                <div class="w-full flex flex-row">
                    @foreach($monthGrid->first() as $day)
                        @include($dayOfWeekView, ['day' => $day])
                    @endforeach
                </div>

                @foreach($monthGrid as $week)
                    <div class="w-full flex flex-row">
                        @foreach($week as $day)
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
        </div>
    </div>

    <div>
        @includeIf($afterCalendarView)
    </div>
</div>

<div>
    <div class="flex">
        <div class="overflow-x-auto w-full">
            <div class="inline-block min-w-full overflow-hidden">

                <div class="w-full flex flex-row">
                    @foreach($monthGrid->first() as $day)
                        <div class="flex-1 h-12 border -mt-px -ml-px" style="min-width: 12rem;">
                            @include($dayOfWeekView, ['day' => $day])
                        </div>
                    @endforeach
                </div>

                @foreach($monthGrid as $week)
                    <div class="w-full flex flex-row">
                        @foreach($week as $day)
                            <div
                                ondragenter="onLivewireCalendarEventDragEnter(event, @this, '{{ $day->format('Y-m-d') }}');"
                                ondragleave="onLivewireCalendarEventDragLeave(event, @this, '{{ $day->format('Y-m-d') }}');"
                                ondragover="onLivewireCalendarEventDragOver(event);"
                                ondrop="onLivewireCalendarEventDrop(event, @this, '{{ $day->format('Y-m-d') }}', {{ $day->year }}, {{ $day->month }}, {{ $day->day }});"
                                class="flex-1 h-48 border border-gray-200 -mt-px -ml-px"
                                style="min-width: 12rem;">
                                <div
                                    class="w-full h-full"
                                    id="{{ $_instance->id }}-{{ $day->format('Y-m-d') }}">
                                @include($dayView, [
                                    'day' => $day,
                                    'dayInMonth' => $day->isSameMonth($startsAt),
                                    'isToday' => $day->isToday(),
                                    'events' => $getEventsForDay($day, $events),
                                ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

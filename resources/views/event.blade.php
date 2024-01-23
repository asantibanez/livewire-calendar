<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="bg-white rounded-lg border py-2 px-3 shadow-md cursor-pointer">

    <p class="text-sm font-medium">
        {{ $event['title'] }}
    </p>
    <p class="mt-2 text-xs">
        {{ $event['description'] ?? 'No description' }}
    </p>
    <p class="mt-2 text-xs">
        {{ $event['recurrenceTime'] ?? 'No description' }}
    </p>
    {{-- <p class="mt-2 text-xs">
        {{ $event['recurrence_day_of_week'] ?? 'No description' }}
    </p> --}}
</div>

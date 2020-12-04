<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="bg-purple-200 rounded-lg border py-1 px-2 shadow-md cursor-pointer">

    <p class="text-xs">
        {{ $event['title'] }}
    </p>
    <p class="mt-1 text-xs">
        {{ $event['description'] ?? 'No description' }}
    </p>
</div>

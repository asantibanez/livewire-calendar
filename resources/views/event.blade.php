<div class="bg-white rounded-lg border py-2 px-3 shadow-md cursor-pointer"
    wire:click.stop="onEventClick({{ $event['id']  }})">
    <p class="text-sm font-medium">
        {{ $event['title'] }}
    </p>
    <p class="mt-2 text-xs">
        {{ $event['description'] ?? 'No description' }}
    </p>
</div>

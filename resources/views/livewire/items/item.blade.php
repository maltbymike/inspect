<div>
    <x-slot name="header">
        <x-page-title>
            {{ __('Items') }}
        </x-page-title>
    </x-slot>
    
    <x-main-content-box>
        <x-content-box-section>
            <div class="grid grid-cols-2 gap-4">
                @foreach ($items as $item)
                    <div>{{ $item->reference }}</div>
                    <div>{{ $item->name }}</div>
                @endforeach
            </div>
        </x-content-box-section>
    </x-main-content-box>
</div>

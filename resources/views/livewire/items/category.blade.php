<div>
    <x-slot name="header">
        <x-page-title>
            {{ $this->category->name ?? __('Categories') }}
        </x-page-title>
    </x-slot>

    <x-main-content-box>
        <x-content-box-section>
            {{ $this->table }}
        </x-content-box-section>
    </x-main-content-box>        

</div>

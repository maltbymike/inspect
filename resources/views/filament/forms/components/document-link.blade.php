<x-filament::link 
    :href="$getRecord()->getSignedUrl()" 
    target="_blank"
    icon="heroicon-o-arrow-top-right-on-square"
    icon-position="after"
>
    {{ $getRecord()->title }}
</x-filament::link>
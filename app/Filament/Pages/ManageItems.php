<?php

namespace App\Filament\Pages;

use App\Models\Items\Item;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Panel;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\Type\VoidType;

class ManageItems extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.items.manage';

    protected static bool $shouldRegisterNavigation = false;

    public Item $record;

    public static function routes(Panel $panel): void
    {
        Route::get('items/{id}/manage', Static::class)
            ->name('items.manage');
    }

    public function mount(int | string $id): void
    {
        $this->record = (new Item)->findOrFail($id);
    }

    public function itemInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->columns(2)
            ->schema([
                TextEntry::make('reference'),
                TextEntry::make('name'),
            ]);
    }
}

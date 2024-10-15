<?php

namespace App\Filament\Pages;

use Filament\Panel;
use Filament\Pages\Page;
use App\Models\Items\Item;
use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use SebastianBergmann\Type\VoidType;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class ViewItem extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.items.view';

    protected static bool $shouldRegisterNavigation = false;

    public Item $record;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.items.index') => 'Items',
            'View',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->url(route('filament.admin.resources.items.edit', ['record' => $this->record])),
        ];
    }

    public static function routes(Panel $panel): void
    {
        Route::get('items/{id}/view', Static::class)
            ->name('items.view');
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

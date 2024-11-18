<?php

namespace App\Livewire\Items;

use Livewire\Component;
use App\Actions\QueueInspectionAction;
use App\Models\Items\Item;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use App\Models\Items\Inspections\ItemTemplate;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Resources\ItemTemplateResource;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class ListItemInspectionTemplates extends Component implements HasForms, HasTable, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithTable;

    public int|Item $item;

    public function mount (Item $item)
    {
        $this->item = $item;
    }

    public function table(Table $table): Table
    {
        return ItemTemplateResource::table($table)
            ->query(ItemTemplate::query()->whereIn('item_id', $this->item->ancestorsAndSelf()->pluck('id')))
            ->heading('Inspection Templates')
            ->actions([
                QueueInspectionAction::make()
            ])
            ->headerActions([
                // 
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-item-inspection-templates');
    }
}

<?php

namespace App\Livewire\Items;

use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Tables;
use Livewire\Component;
use App\Models\Items\Item;
use Filament\Tables\Table;
use Awcodes\Curator\Models\Media;
use Filament\Actions\StaticAction;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Models\Items\Inspections\ItemTemplate;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\Items\Inspections\ItemInspection;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;

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
        return $table
            ->query(ItemTemplate::query()->whereIn('item_id', $this->item->itemAndParentsIdArray()))
            ->heading('Inspection Templates')
            ->columns([
                Tables\Columns\TextColumn::make('item.reference')
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('template.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Queue Inspection')
                    ->modalContent(view('filament.pages.actions.queue-inspections'))
                    ->modalHeading(false)
                    ->modalSubmitAction(fn (StaticAction $action) => $action->icon('heroicon-o-arrow-left-start-on-rectangle'))
                    ->modalSubmitActionLabel('Queue Inspection')
                    ->extraModalFooterActions(fn (Tables\Actions\Action $action): array => [
                        $action
                            ->makeModalSubmitAction('queueInspectionAndView', arguments: ['redirect' => true])
                            ->label('Queue Inspection and View')
                            ->color('info')
                            ->icon('heroicon-m-pencil-square')
                    ])
                    ->action(
                        function (ItemTemplate $record, array $arguments, $livewire): void {                        
                            $inspection = new ItemInspection;
                            if ($livewire instanceof InspectionTemplatesRelationManager) {
                                $inspection->item_id = $livewire->getOwnerRecord()->id;
                            } else {
                                $inspection->item_id = $record->item_id;
                            }
                            $inspection->item_template_id = $record->id;
                            $inspection->save();

                            if ($arguments['redirect'] ?? false) {
                                redirect()->route('filament.admin.resources.item-inspections.edit', ['record' => $inspection->id]);
                            } else {
                                redirect(request()->header('Referer'));
                            }
                        } 
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-item-inspection-templates');
    }
}

<?php

namespace App\Livewire\Items;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Set;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Items\Item;
use Filament\Tables\Table;
use Awcodes\Curator\Models\Media;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use App\Models\Items\Inspections\ItemTemplate;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\Items\Inspections\ItemInspection;
use Filament\Tables\Concerns\InteractsWithTable;

class ListItemInspections extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public int|Item $item;

    public function mount (Item $item)
    {
        $this->item = $item;
    }

    public function formSchema()
    {
        return [
                Select::make('item_id')
                    ->relationship('item', 'name')
                    ->disabledOn('edit')
                    ->required(),
                Select::make('item_template_id')
                    ->label('Inspection Template')
                    ->options(fn (ItemInspection $record): Collection =>
                        ItemTemplate::whereIn('item_id', $record->item->itemAndParentsIdArray())
                            ->with('template')
                            ->get()
                            ->pluck('template.name', 'id')
                    )
                    ->disabledOn('edit')
                    ->required(),
                DateTimePicker::make('created_at')
                    ->readonly(),
                DateTimePicker::make('started_at')
                    ->disabled(fn (ItemInspection $record): bool => $record->inspectionIsNotStarted())
                    ->readonly(),
                DateTimePicker::make('completed_at')
                    ->disabled(fn (ItemInspection $record): bool => $record->inspectionIsNotCompleted())
                    ->readonly(),
                Select::make('completed_by_user_id')
                    ->relationship('completedByUser', 'name')
                    ->disabled(),
                Actions::make([
                    Action::make('startInspection')
                        ->color('success')
                        ->label(__('Start Inspection'))
                        ->disabled(fn (ItemInspection $record): bool => $record->inspectionIsStarted())
                        ->action(
                            function (ItemInspection $record, Set $set): void {
                                $record->started_at = now();
                                $record->save();
                                $set('started_at', now()->toDateTimeString());
                            } 
                        ),
                ])
                ->columnSpanFull()
                ->fullWidth(),
                Section::make('Inspection Information')
                    ->relationship('itemTemplate')
                    ->schema([
                        Repeater::make('documents')
                            ->relationship('documents')
                            ->grid(3)
                            ->simple(
                                TextInput::make('title')
                                    ->readOnly()
                                    ->suffixAction(
                                        Action::make('viewDocument')
                                            ->icon('heroicon-o-arrow-right-start-on-rectangle')
                                            ->modalHeading(fn (Media $record): string => $record->title)
                                            ->modalCancelAction(false)
                                            ->modalSubmitAction(false)
                                            ->modalContent(fn (Media $record): View => view(
                                                'filament.pages.actions.embed-pdf',
                                                ['record' => $record],
                                            ))
                                    )
                            ),
                        RichEditor::make('description')
                            ->toolbarButtons([]),
                        
                    ]),
                Actions::make([
                    Action::make('completeInspection')
                        ->color('danger')
                        ->label(__('Complete Inspection'))
                        ->visible(
                            fn (ItemInspection $record): bool => 
                                $record->inspectionIsStarted()
                        )
                        ->disabled(fn (ItemInspection $record): bool => $record->inspectionIsCompleted())
                        ->form([
                            Select::make('completed_by')
                                ->options(User::query()->pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(
                            function (array $data, ItemInspection $record, Set $set): void {
                                $record->completed_at = now();
                                $record->completedByUser()->associate($data['completed_by']);
                                $record->save();
                                $set('completed_at', now()->toDateTimeString());
                            } 
                        ),
                ])
                ->columnSpanFull()
                ->fullWidth(),
            ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ItemInspection::query()->where('item_id', $this->item->id))
            ->heading('Inspections')
            ->columns([
                Tables\Columns\TextColumn::make('itemTemplate.template.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedToUser.name')
                    ->label(__('Assigned To'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label(__('Started At'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('Completed At'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completedByUser.name')
                    ->label(__('Completed By'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approvedByUser.name')
                    ->label(__('Approved By'))
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('Completed')
                    ->attribute('completed_at')
                    ->placeholder('Show All')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->nullable()
                    ->default(false),
            ])
            ->actions([
                ViewAction::make()
                    ->record($this->item)
                    ->form([
                        Section::make()
                            ->columns(2)
                            ->schema($this->formSchema())
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-item-inspections');
    }
}

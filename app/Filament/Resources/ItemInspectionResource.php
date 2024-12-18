<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Grouping\Group;
use App\Traits\HasStandardTableActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\ItemInspection;
use Filament\Tables\Columns\Summarizers\Average;
use App\Filament\Resources\ItemInspectionResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\ItemInspectionResource\Pages\ListItemInspections;

class ItemInspectionResource extends Resource implements HasShieldPermissions
{
    use HasStandardTableActions;

    protected static ?string $model = ItemInspection::class;

    protected static ?string $navigationGroup = 'Items';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function formSchema(): array
    {        
        return [
            Forms\Components\Section::make(function (ItemInspection $record): string {
                return $record->itemTemplate->type->name;
            })
                ->description(fn (ItemInspection $record): string => $record->item->name)
                ->columns(2)
                ->collapsed()
                ->schema([
                    Forms\Components\Fieldset::make('Timestamps')
                        ->columnSpan(1)
                        ->schema([
                            Forms\Components\DateTimePicker::make('created_at'),
                            Forms\Components\DateTimePicker::make('started_at'),
                            Forms\Components\DateTimePicker::make('completed_at'),
                        ]),
                    Forms\Components\Fieldset::make('Responsible Users')
                        ->columnSpan(1)
                        ->schema([
                        Forms\Components\Select::make('assigned_to_user_id')
                            ->relationship('assignedToUser', 'name'),
                        Forms\Components\Select::make('completed_by_user_id')
                            ->relationship('completedByUser', 'name'),
                        Forms\Components\Select::make('approved_by_user_id')
                            ->relationship('approvedByUser', 'name'),
                        ]),
                    Forms\Components\Fieldset::make('Meter Readings')
                        ->columnSpan(1)
                        ->relationship('meter')
                        ->schema([
                            Forms\Components\TextInput::make('meter_start')
                                ->label(__('Meter Start')),
                            Forms\Components\TextInput::make('meter_end')
                                ->label(__('Meter End')),
                        ])
                ]),
            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('startInspection')
                    ->action(function (array $data, ItemInspection $record, Set $set): void {
                            $record->started_at = now();
                            if (array_key_exists('meterStart', $data) )
                            {
                                $record->meter()->create([
                                    'item_id' => $record->item_id,
                                    'meter_start' => $data['meterStart'],
                                ]);
                            }
                            
                            $record->save();
                            $set('started_at', now()->toDateTimeString());
                        } 
                    )
                    ->color('success')
                    ->disabled(fn (ItemInspection $record): bool => $record->inspectionIsStarted())
                    ->form(function (ItemInspection $record) {
                        if ($record->item->has_inspection_meter) {
                            return [
                                TextInput::make('meterStart')
                                    ->label(__('Meter Reading'))
                                    ->numeric()
                                    ->step(.1)
                                    ->required()
                                    ->hintAction(
                                        Action::make('copyLastMeterToMeterStart')
                                            ->label(function (ItemInspection $record) {
                                                return 'Use Current Reading: ' . $record->item->getLastMeterReading();
                                            })
                                            ->action(function (Set $set, ItemInspection $record) {
                                                $set('meterStart', $record->item->getLastMeterReading());    
                                            })
                                    ),
                            ]; 
                        }
                    }
                    )
                    ->label(__('Start Inspection'))
            ])
            ->columnSpanFull()
            ->fullWidth(),
            Forms\Components\Section::make('Inspection Information')
                ->relationship('itemTemplate')
                ->schema([
                    Forms\Components\Repeater::make('documents')
                        ->relationship('documents')
                        ->addable(false)
                        ->deletable(false)
                        ->simple(
                            Forms\Components\ViewField::make('title')
                                ->view('filament.forms.components.document-link')
                        ),
                    Forms\Components\Placeholder::make('description')
                        ->content(function (ItemTemplate $record) {
                            return new HtmlString(str($record->description)->sanitizeHtml());
                        }),                        
                ]),
            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('completeInspection')
                    ->color('danger')
                    ->label(__('Complete Inspection'))
                    ->visible(
                        fn (ItemInspection $record): bool => 
                            $record->inspectionIsStarted()
                    )
                    ->disabled(fn (ItemInspection $record): bool => $record->inspectionIsCompleted())
                    ->form(function (ItemInspection $record) {
                        if ($record->item->has_inspection_meter) {
                            $returnArray[] = 
                                TextInput::make('meterEnd')
                                    ->label(__('Meter Reading'))
                                    ->required()
                                    ->numeric()
                                    ->step(.1)
                                    ->hintAction(
                                        Action::make('copyMeterStartToMeterEnd')
                                            ->label(fn (ItemInspection $record): string => 'Use Current Reading: ' . $record->meter->meter_start)
                                            ->action(function (Set $set, ItemInspection $record) {
                                                $set('meterEnd', $record->meter->meter_start);    
                                            })
                                    );
                        }

                        $returnArray[] = Forms\Components\Select::make('completed_by')
                                ->options(User::query()->pluck('name', 'id'))
                                ->required();
                        
                        return $returnArray;
                    })
                    ->action(
                        function (array $data, ItemInspection $record, Set $set): void {
                            $record->completed_at = now();
                            array_key_exists('meterEnd', $data) 
                                ? $record->meter->meter_end = $data['meterEnd']
                                : null;
                            $record->completedByUser()->associate($data['completed_by']);
                            $record->push();
                            $set('completed_at', now()->toDateTimeString());
                        } 
                    ),
            ])
            ->columnSpanFull()
            ->fullWidth(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Static::formSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('item.id')
            ->groups([
                Group::make('item.id')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(fn (ItemInspection $record): string => $record->item->reference . ": " . $record->item->name)
                    ->collapsible(),
                Group::make('item.parent_id')
                    ->label('Item Group')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function (ItemInspection $record): string  
                        {
                            return is_null($record->item->parent) 
                                ? $record->item->reference . ": " . $record->item->name
                                : $record->item->parent->reference . ": " . $record->item->parent->name;
                        }
                    )
                    ->collapsible(),
                Group::make('itemTemplate.id')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(fn (ItemInspection $record): string => $record->itemTemplate->type->name)
                    ->collapsible(),
                Group::make('completedByUser.id')
                    ->getTitleFromRecordUsing(fn (ItemInspection $record): string => !is_null($record->completedByUser) ? $record->completedByUser->name : '')
                    ->collapsible(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('item.reference')
                    ->label('Item')
                    ->sortable(),
                Tables\Columns\TextColumn::make('itemTemplate.type.name')
                    ->label('Inspection Item')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedToUser.name')
                    ->label(__('Assigned To'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label(__('Started At'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('Completed At'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inspection_time_in_minutes')
                    ->label(__('Inspection Time'))
                    ->summarize(Average::make())
                    ->sortable(),
                Tables\Columns\TextColumn::make('meter.meter_start')
                    ->label(__('Meter Start'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('meter.meter_end')
                    ->label(__('Meter End'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('completedByUser.name')
                    ->label(__('Completed By'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_by_user_id')
                    ->label(__('Approved By'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('Completed')
                    ->attribute('completed_at')
                    ->placeholder('Show All')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->nullable()
                    ->hiddenOn(ListItemInspections::class)
                    ->default(false),
                Tables\Filters\SelectFilter::make('AssignedTo')
                    ->options(User::permission('update_item::inspection')->pluck('name', 'id'))
                    ->attribute('assigned_to_user_id')
                    ->default(auth()->user()->id),
                Tables\Filters\SelectFilter::make('Item')
                    ->relationship('item', 'reference'),
                Tables\Filters\SelectFilter::make('Item Group')
                    ->relationship('item.parent', 'reference'),
                Tables\Filters\SelectFilter::make('Inspection Type')
                    ->relationship('itemTemplate.type', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make(
                    Static::StandardTableActions(),
                )
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemInspections::route('/'),
            'create' => Pages\CreateItemInspection::route('/create'),
            'view' => Pages\ViewItemInspection::route('/{record}'),
            'edit' => Pages\EditItemInspection::route('/{record}/edit'),
            'edit-history' => Pages\EditHistory::route('/{record}/edit/history'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return config('filament-shield.permission_prefixes.resource');
    }
}

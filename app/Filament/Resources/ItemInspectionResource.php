<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Grouping\Group;
use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\ItemInspection;
use App\Filament\Resources\ItemInspectionResource\Pages;
use Illuminate\Support\HtmlString;

class ItemInspectionResource extends Resource
{
    protected static ?string $model = ItemInspection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function formSchema(): array
    {
        return [
            Forms\Components\Section::make(fn (ItemInspection $record): string => $record->itemTemplate->template->name)
                ->description(fn (ItemInspection $record): string => $record->item->name)
                ->columns(2)
                ->collapsed()
                ->schema([
                    Forms\Components\DateTimePicker::make('created_at'),
                    Forms\Components\DateTimePicker::make('started_at'),
                    Forms\Components\DateTimePicker::make('completed_at'),
                    Forms\Components\Select::make('completed_by_user_id')
                        ->relationship('completedByUser', 'name'),
                ]),
            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('startInspection')
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
                    ->form([
                        Forms\Components\Select::make('completed_by')
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Static::formSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup(
                Group::make('item.id')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(fn (ItemInspection $record): string => $record->item->reference . ": " . $record->item->name)
                    ->collapsible()
            )
            ->columns([
                Tables\Columns\TextColumn::make('item.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('itemTemplate.template.name')
                    ->label('Inspection Item')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedToUser.name')
                    ->label(__('Assigned To'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
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
                    ->default(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
        ];
    }
}

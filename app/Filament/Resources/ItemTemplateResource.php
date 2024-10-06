<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\StaticAction;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\ItemInspection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemTemplateResource\Pages;
use App\Filament\Resources\ItemTemplateResource\RelationManagers;

class ItemTemplateResource extends Resource
{
    protected static ?string $model = ItemTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->relationship(name: 'item', titleAttribute: 'name')
                    ->disabledOn('edit')
                    ->required(),
                Forms\Components\Select::make('template_id')
                    ->relationship(name: 'template', titleAttribute: 'name')
                    ->disabledOn('edit')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name'),
                    ]),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('template.name')
                    ->numeric()
                    ->sortable(),
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
                    function (ItemTemplate $record, array $arguments): void {                        
                        $inspection = new ItemInspection;
                        $inspection->item_id = $record->item_id;
                        $inspection->item_template_id = $record->id;
                        $inspection->save();

                        if ($arguments['redirect'] ?? false) {
                            redirect()->route('filament.admin.resources.item-inspections.edit', ['record' => $inspection->id]);
                        }
                    } 
                ),
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
            'index' => Pages\ListItemTemplates::route('/'),
            'create' => Pages\CreateItemTemplate::route('/create'),
            'edit' => Pages\EditItemTemplate::route('/{record}/edit'),
        ];
    }
}

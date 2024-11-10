<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\RelationManagers\InspectionTemplatesRelationManager;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\StaticAction;
use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\ItemInspection;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use App\Filament\Resources\ItemTemplateResource\Pages;
use Filament\Resources\RelationManagers\RelationManager;

class ItemTemplateResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = ItemTemplate::class;

    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->relationship(name: 'item', titleAttribute: 'name')
                    ->disabled()

                    ->default(fn (InspectionTemplatesRelationManager $livewire): int => $livewire->getOwnerRecord()->id)
                    ->required(),
                Forms\Components\Select::make('template_id')
                    ->relationship(name: 'type', titleAttribute: 'name')
                    ->disabledOn('edit')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name'),
                    ]),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                CuratorPicker::make('documents')
                    ->multiple()
                    ->relationship('documents', 'id')
                    ->directory('inspection_documents'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type.name')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            'view' => Pages\ViewItemTemplate::route('/{record}'),
            'edit' => Pages\EditItemTemplate::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return config('filament-shield.permission_prefixes.resource');
    }
}

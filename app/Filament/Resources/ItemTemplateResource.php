<?php

namespace App\Filament\Resources;

use App\Actions\QueueInspectionAction;
use App\Filament\Resources\ItemResource\RelationManagers\InspectionTemplatesRelationManager;
use App\Traits\HasStandardTableActions;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\Items\Inspections\ItemTemplate;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use App\Filament\Resources\ItemTemplateResource\Pages;

class ItemTemplateResource extends Resource implements HasShieldPermissions
{
    use HasStandardTableActions;

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
                Forms\Components\Select::make('type_id')
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
                QueueInspectionAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make(
                    Static::StandardTableActions()
                ),
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
            'edit-history' => Pages\EditHistory::route('/{record}/edit/history'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return config('filament-shield.permission_prefixes.resource');
    }
}

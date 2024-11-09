<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Items\Item;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Resources\ItemResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class ItemResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('reference')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
            'edit-history' => Pages\EditHistory::route('/{record}/edit/history'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return config('filament-shield.permission_prefixes.resource_with_soft_deletes');
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Inspections', [
                RelationManagers\InspectionsRelationManager::class,
                RelationManagers\InspectionTemplatesRelationManager::class,
            ]),
            RelationGroup::make('Item Information', [
                RelationManagers\CategoriesRelationManager::class,
                RelationManagers\ChildrenRelationManager::class,
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn (Item $record) => $record->trashed() ? 'bg-danger-100' : '')
            ->recordUrl(fn (Item $record) => $record->trashed() ? null : route('filament.admin.pages.items.view', ['id' => $record->id]))
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
                Tables\Filters\TrashedFilter::make()
                    ->label('Inactive Items')
                    ->placeholder('Without Inactive Items')
                    ->trueLabel('With Inactive Items')
                    ->falseLabel('Only Inactive Items'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->color('gray')
                    ->disabled(fn ($record) => $record->trashed())
                    ->url(fn ($record): string => route('filament.admin.pages.items.view', ['id' => $record->id] )),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->url(fn (Item $record): string => ItemResource::getUrl('edit', ['record' => $record])),
                    Tables\Actions\Action::make('edit-history')
                        ->icon('heroicon-m-document-magnifying-glass')
                        ->url(fn ($record) => ItemResource::getUrl('edit-history', ['record' => $record])),
                    Tables\Actions\DeleteAction::make()
                        ->label('Make Inactive'),
                    Tables\Actions\RestoreAction::make()
                        ->label('Make Active')
                        ->color('success'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Make Selected Inactive'),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Make Selected Active')
                        ->color('success'),
                ]),
            ]);
    }
}

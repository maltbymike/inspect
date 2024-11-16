<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Items\Category;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use App\Traits\HasStandardTableActions;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\CategoryResource\Pages;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    use HasStandardTableActions;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Items';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->columnSpan(3)
                    ->required(),
                TextInput::make('slug')
                    ->columnSpan(3)
                    ->required(),
                Toggle::make('is_root')
                    ->label('Root Category')
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'mt-1'])
                    ->inline(false)
                    ->required(),
            ])
            ->columns(7);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_root')
                    ->boolean(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make(
                    Static::StandardTableActions(),
                ),
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
            RelationGroup::make('Items', [
                RelationManagers\ItemsRelationManager::class,
            ]),
            RelationGroup::make('Child Categories', [
                RelationManagers\ChildrenRelationManager::class,
            ]),
            RelationGroup::make('Parent Categories', [

                RelationManagers\ParentsRelationManager::class,
            ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
            'edit-history' => Pages\EditHistory::route('/{record}/edit/history'),
        ];
    }
}

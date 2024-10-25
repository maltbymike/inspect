<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use App\Models\Items\Item;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\ItemResource;
use Filament\Tables\Actions\AttachAction;
use Filament\Resources\RelationManagers\RelationManager;

class ParentsRelationManager extends RelationManager
{
    protected static string $relationship = 'parents';

    public function form(Form $form): Form
    {
        return ItemResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ItemResource::table($table)
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name', 'reference'])
                    ->recordTitle(fn (Item $record): string => "{$record->reference} {$record->name}")
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->inverseRelationship('children');
    }
}

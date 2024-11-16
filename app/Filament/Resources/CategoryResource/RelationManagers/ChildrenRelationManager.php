<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Filament\Resources\CategoryResource;
use App\Models\Items\Category;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    public function form(Form $form): Form
    {
        return CategoryResource::form($form);
    }

    public function table(Table $table): Table
    {
        return CategoryResource::table($table)
            ->actions([
                Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Category $record): string => route('filament.admin.resources.categories.view', $record))
            ]);
    }
}

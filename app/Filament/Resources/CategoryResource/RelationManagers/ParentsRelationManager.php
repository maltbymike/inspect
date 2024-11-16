<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Items\Category;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\CategoryResource;
use Filament\Resources\RelationManagers\RelationManager;

class ParentsRelationManager extends RelationManager
{
    protected static string $relationship = 'parents';

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

<?php

namespace App\Filament\Resources\ItemTemplateTypeResource\RelationManagers;

use App\Filament\Resources\ItemResource;
use App\Models\Items\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return ItemResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ItemResource::table($table);
    }
}

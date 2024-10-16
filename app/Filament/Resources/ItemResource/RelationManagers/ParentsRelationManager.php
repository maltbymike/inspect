<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\ItemResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
        return ItemResource::table($table);
    }
}

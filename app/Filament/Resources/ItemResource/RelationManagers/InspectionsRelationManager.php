<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use App\Filament\Resources\ItemInspectionResource;
use App\Models\Items\Inspections\ItemInspection;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Component;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Items\Inspections\Template;
use App\Models\Items\Inspections\ItemTemplate;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Carbon;

class InspectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'inspections';

    public function form(Form $form): Form
    {
        return ItemInspectionResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ItemInspectionResource::table($table);
    }
}

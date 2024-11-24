<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use App\Filament\Resources\ItemTemplateResource;
use App\Models\Items\Item;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\ItemInspection;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Builder;

class InspectionTemplatesRelationManager extends RelationManager
{
    protected static string $relationship = 'inspectionTemplates';

    public function form(Form $form): Form
    {
        return ItemTemplateResource::form($form);
    }

    public function table(Table $table): Table
    {
        return ItemTemplateResource::table($table)
            ->query(ItemTemplate::query()->whereIn('item_id', $this->getOwnerRecord()->ancestorsAndSelf()->pluck('id')));
    }
}

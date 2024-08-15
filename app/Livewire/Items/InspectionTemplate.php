<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class InspectionTemplate extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    // public ?\App\Models\Items\Inspections $inspection;
    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Items\Inspections\Template::query())
            ->columns([
                // ...
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.items.inspection');
    }
}

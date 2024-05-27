<?php

namespace App\Livewire\Items;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ViewAction;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class Item extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Items\Item::query())
            ->columns([
                TextColumn::make('reference'),
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ViewAction::make()
                    ->form([
                        TextInput::make('reference')
                            ->string()
                            ->required(),
                        TextInput::make('name')
                            ->string()
                            ->required(),
                        Repeater::make('inspectionTemplates')
                            ->relationship()
                            ->simple(
                                TextInput::make('name')
                                    ->string()
                                    ->required(),
                            )
                    ])
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.items.item', [
            'items' => \App\Models\Items\Item::all(),
        ]);
    }
}

<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class InspectionTemplate extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected function itemTemplateForm(): array
    {
        return [
            TextInput::make('name')
                ->string()
                ->required(),
            RichEditor::make('description')
                ->string(),
        ];
        
    }

    // public ?\App\Models\Items\Inspections $inspection;
    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Items\Inspections\Template::query())
            ->columns([
                TextColumn::make('name')
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ViewAction::make()
                    ->form(
                        $this->itemTemplateForm()
                    ),
                EditAction::make()
                    ->form(
                        $this->itemTemplateForm()
                    ),
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

<?php

namespace App\Livewire\Items;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public $category;

    protected function itemInspectionForm(): array
    {
        return [
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
        ];
        
    }

    public function mount($category = null)
    {
        $this->category = $category;
    }

    public function table(Table $table): Table
    {
        if (is_null($this->category)) {
            $table->query(\App\Models\Items\Item::query());
        } else {
            $table->relationship(fn (): BelongsToMany => $this->category->items())
                ->inverseRelationship('categories');
        }

        return $table
            ->columns([
                TextColumn::make('reference'),
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ViewAction::make()
                    ->form(
                        $this->itemInspectionForm()
                    ),
                EditAction::make()
                    ->form(
                        $this->itemInspectionForm()
                    ),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        if (! is_null($this->category)) {
            $itemCount = $this->category->items()->get()->count();
        }

        return view('livewire.items.item', [
            'items' => \App\Models\Items\Item::all(),
            'showItems' => $itemCount ?? true,
        ]);
    }
}

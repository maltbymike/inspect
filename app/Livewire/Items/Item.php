<?php

namespace App\Livewire\Items;

use App\Models\Items\Category;
use App\Models\Items\CategoryParentChild;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Collection;
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

    protected function getSubcategoryIds(int $categoryId, Collection $pivotCollection, bool $includeCategory = false)
    {
        $subcategories = $pivotCollection->where('parent_id', $categoryId)
                                            ->pluck('child_id');
        
        foreach ($subcategories as $subcategory) {
            $subcategories->push(
                $this->getSubcategoryIds(
                    categoryId: $subcategory, 
                    pivotCollection: $pivotCollection
                )
            );
        }

        if ($includeCategory) {
            $subcategories->push($categoryId);
        }

        return $subcategories->flatten()->all();
    }

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
        if (! $this->category || ! $this->category->exists) {
            $table->query(\App\Models\Items\Item::query());
        } else {
            $categoryParentChild = CategoryParentChild::all();

            $subcategories = $this->getSubcategoryIds(
                categoryId: $this->category->id, 
                pivotCollection: $categoryParentChild,
                includeCategory: true,
            );

            $table->query(\App\Models\Items\Item::whereHas('categories', function($query) use ($subcategories) {
                $query->whereIn('item_category_item.category_id', $subcategories);
            }));

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
        return view('livewire.items.item', [
            'items' => \App\Models\Items\Item::all(),
        ]);
    }
}

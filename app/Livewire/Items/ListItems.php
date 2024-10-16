<?php

namespace App\Livewire\Items;

use App\Models\Items\Item;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Items\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use App\Models\Items\CategoryParentChild;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ListItems extends Component implements HasForms, HasTable
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
                    Select::make('template_id')
                        ->relationship(name: 'template', titleAttribute: 'name')
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required(),
                        ]),
                ),
            Repeater::make('inspectionTemplatesFromParents')
                ->label('Inspection Templates from Parents')
                ->addable(false)
                ->deletable(false)
                ->relationship()
                ->simple(
                    Repeater::make('inspectionTemplates')
                        ->addable(false)
                        ->deletable(false)
                        ->relationship()
                        ->simple(
                            Select::make('template_id')
                                ->disabled()
                                ->relationship(name: 'template', titleAttribute: 'name'),
                        ),
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
                Tables\Actions\Action::make('view-item')
                    ->url(fn (Item $record): string => route('item', $record)),
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
        return view('livewire.items.list-items', [
            'items' => \App\Models\Items\Item::all(),
        ]);
    }
}

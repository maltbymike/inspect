<?php

namespace App\Livewire\Items;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public ?\App\Models\Items\Category $category;
    
    public function getItemCategoryForm(): array
    {
        return [
            TextInput::make('name')
                ->string()
                ->required()
                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                    if (! $get('is_slug_changed_manually') && filled($state)) {
                        $set('slug', Str::slug($state));
                    }
                })
                ->reactive(),
            TextInput::make('slug')
                ->string()
                ->required()
                ->afterStateUpdated(function (Set $set) {
                    $set('is_slug_changed_manually', true);
                }),
            Hidden::make('is_slug_changed_manually')
                ->default(false)
                ->dehydrated(false),
        ];
    }

    public function mount(\App\Models\Items\Category $category): void
    {
        $this->category = $category;
    }

    public function table(Table $table): Table
    {
        if($this->category->exists) {
            $table
            ->relationship(fn (): ?BelongsToMany => $this->category->children())
            ->inverseRelationship('parents');
        } else {
            $table->query(\App\Models\Items\Category::query()->where('is_root', true));
        }

        return $table
            ->columns([
                TextColumn::make('name')
                    ->url(fn(\App\Models\Items\Category $category) => route('item_categories', ['category' => $category])),
                TextColumn::make('slug')
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ViewAction::make()
                    ->form(
                        $this->getItemCategoryForm()
                    ),
                EditAction::make()
                    ->form(
                        $this->getItemCategoryForm()
                    ),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        if($this->category->exists) {
            $categoryCount = $this->category->children()->get()->count();
        } else {
            $categoryCount = \App\Models\Items\Category::where('is_root', true)->get()->count();
        }

        return view('livewire.items.category', [
            'categories' => \App\Models\Items\Category::all(),
            'showCategory' => $categoryCount > 0 ? true : false,
        ]);
    }
}

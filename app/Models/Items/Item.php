<?php

namespace App\Models\Items;

use App\Models\Items\Inspections\ItemInspection;
use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\Template;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasGraphRelationships;

class Item extends Model
{
    use HasGraphRelationships;
    use HasFactory;

    protected $guarded = [];

    public function getPivotTableName(): string
    {
        return 'items_parent_child';
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'item_category_item',
            'item_id',
            'category_id',
        );
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(
            ItemInspection::class
        );
    }

    public function inspectionTemplates(): HasMany
    {
        return $this->hasMany(ItemTemplate::class);
    }

    public function inspectionTemplatesFromParents(): BelongsToMany
    {
        return $this->parents()->with('inspectionTemplates');
    }

    public function itemAndParentsIdArray(): Array
    {
        return $this->parents()->get()
            ->pluck('id')
            ->push($this->id)
            ->toArray();
    }

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(
            Template::class,
            'item_template',
            'item_id',
            'template_id',
        );
    }
}

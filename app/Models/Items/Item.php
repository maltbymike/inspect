<?php

namespace App\Models\Items;

use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\ItemInspection;
use App\Models\Items\Inspections\Template;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Item extends Model
{
    use HasRecursiveRelationships;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function getParentKeyName(): string
    {
        return 'parent_id';
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

    /**
     * @deprecated use types() instead
     * Summary of templates
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(
            Template::class,
            'item_template',
            'item_id',
            'type_id',
        );
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(
            Template::class,
            'item_template',
            'item_id',
            'type_id',
        );
    }
}

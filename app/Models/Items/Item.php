<?php

namespace App\Models\Items;

use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\Template;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class,
            'item_category_item',
            'item_id',
            'category_id',
        );
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class, 
            'items_parent_child',
            'parent_id',
            'child_id',
        );
    }

    public function inspectionTemplates(): HasMany
    {
        return $this->hasMany(ItemTemplate::class);
    }

    public function inspectionTemplatesFromParents()
    {
        return $this->parents()->with('inspectionTemplates');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class, 
            'items_parent_child',
            'child_id',
            'parent_id',
        );
    }
}

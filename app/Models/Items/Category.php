<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'item_categories';

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class, 
            'item_categories_parent_child',
            'parent_id',
            'child_id',
        );
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class,
            'item_category_item',
            'category_id',
            'item_id',
        );
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class, 
            'item_categories_parent_child',
            'child_id',
            'parent_id',
        );
    }
}

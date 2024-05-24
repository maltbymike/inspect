<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class, 
            'item_parent_child',
            'parent_id',
            'child_id',
        );
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class, 
            'item_parent_child',
            'child_id',
            'parent_id',
        );
    }
}

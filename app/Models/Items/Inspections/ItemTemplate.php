<?php

namespace App\Models\Items\Inspections;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ItemTemplate extends Pivot implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public $incrementing = true;

    public function inspections(): HasMany
    {
        return $this->hasMany(ItemInspection::class, 'item_template_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
    
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}

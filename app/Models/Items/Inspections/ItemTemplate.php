<?php

namespace App\Models\Items\Inspections;

use App\Models\Items\Item;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemTemplate extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'item_template_media', 'item_template_id', 'media_id')
            ->withPivot('order')
            ->orderBy('order');
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(ItemInspection::class, 'item_template_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function itemTemplateDocuments(): HasMany
    {
        return $this->hasMany(ItemTemplateMedia::class, 'item_template_id', 'id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}

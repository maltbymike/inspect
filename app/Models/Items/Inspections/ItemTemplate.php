<?php

namespace App\Models\Items\Inspections;

use App\Models\Items\Item;
use Awcodes\Curator\Models\Media;
use Filament\Actions\Concerns\BelongsToGroup;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemTemplate extends Pivot
{
    use HasFactory;
    use LogsActivity;

    public $incrementing = true;

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'item_template_media', 'item_template_id', 'media_id')
            ->withPivot('order')
            ->orderBy('order');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded();
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

    public function type(): BelongsTo
    {
        return $this->belongsTo(ItemTemplateType::class, 'type_id', 'id');
    }
}

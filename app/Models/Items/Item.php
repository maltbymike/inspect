<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Items\Inspections\ItemTemplate;
use App\Models\Items\Inspections\ItemInspection;
use App\Models\Items\Inspections\ItemTemplateType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Item extends Model
{
    use HasRecursiveRelationships;
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $guarded = [];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'item_category_item',
            'item_id',
            'category_id',
        );
    }

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded();
    }

    public function getParentKeyName(): string
    {
        return 'parent_id';
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

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(
            ItemTemplateType::class,
            'item_template',
        );
    }
}

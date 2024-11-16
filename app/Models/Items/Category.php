<?php

namespace App\Models\Items;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasGraphRelationships;

class Category extends Model
{
    use HasFactory;
    use HasGraphRelationships;
    use HasRelationships;
    use LogsActivity;

    protected $guarded = [];

    protected $table = 'item_categories';

    public function descendantItems(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->descendantsAndSelf(),
            (new static)->items()
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded();
    }
    
    public function getPivotTableName(): string
    {
        return 'item_categories_parent_child';
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
}

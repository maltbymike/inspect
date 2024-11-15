<?php

namespace App\Models\Items\Inspections;

use App\Models\Items\Item;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ItemTemplateType extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];
    
    protected $table = 'item_template_types';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded();
    }
    
    public function getForeignKey(): string
    {
        return "type_id";
    }

    public function itemTemplate(): HasMany
    {
        return $this->hasMany(ItemTemplate::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, table: 'item_template');
    }
}

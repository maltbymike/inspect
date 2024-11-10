<?php

namespace App\Models\Items\Inspections;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemTemplateTypes extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = 'item_template_types';

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}

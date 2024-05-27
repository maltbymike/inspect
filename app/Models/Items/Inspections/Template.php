<?php

namespace App\Models\Items\Inspections;

use App\Models\Items\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Template extends Model
{
    use HasFactory;

    protected $table = 'item_inspection_templates';

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}

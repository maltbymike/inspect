<?php

namespace App\Models\Items\Inspections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemInspection extends Model
{
    use HasFactory;

    public function itemTemplate(): BelongsTo
    {
        return $this->belongsTo(ItemTemplate::class);
    }
}

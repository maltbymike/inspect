<?php

namespace App\Models\Items\Inspections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemTemplate extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
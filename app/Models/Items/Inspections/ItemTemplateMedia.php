<?php

namespace App\Models\Items\Inspections;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemTemplateMedia extends Pivot
{
    public $incrementing = true;

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}

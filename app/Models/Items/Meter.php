<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meter extends Model
{
    protected $guarded = [];

    protected $table = 'item_meters';

    public function meterable(): MorphTo
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryParentChild extends Pivot
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'item_categories_parent_child';
}

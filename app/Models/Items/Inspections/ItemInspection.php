<?php

namespace App\Models\Items\Inspections;

use App\Models\Items\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemInspection extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'item_item_inspection';

    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function completedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_user_id');
    }

    public function inspectionIsCompleted(): bool
    {
        return ! is_null($this->completed_at);
    }

    public function inspectionIsNotCompleted(): bool
    {
        return is_null($this->completed_at);
    }

    public function inspectionIsStarted(): bool
    {
        return ! is_null($this->started_at);
    }

    public function inspectionIsNotStarted(): bool
    {
        return is_null($this->started_at);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function itemTemplate(): BelongsTo
    {
        return $this->belongsTo(ItemTemplate::class);
    }

}

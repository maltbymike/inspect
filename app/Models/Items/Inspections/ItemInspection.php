<?php

namespace App\Models\Items\Inspections;

use App\Models\User;
use App\Models\Items\Item;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemInspection extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    protected $table = 'item_item_inspection';

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function completedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_user_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded();
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

    public function itemTemplate(): BelongsTo
    {
        return $this->belongsTo(ItemTemplate::class);
    }

}

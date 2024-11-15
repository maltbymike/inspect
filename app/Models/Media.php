<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Media extends \Awcodes\Curator\Models\Media
{
    use LogsActivity;

    protected $guarded = [
        'file',
    ];

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded();
    }
}

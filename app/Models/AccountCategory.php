<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountCategory extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->logOnlyDirty();
    }

    public function accountClass(): BelongsTo
    {
        return $this->belongsTo(AccountClass::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AccountCategory::class, 'parent_id');
    }
}

<?php

namespace App\Models;

use App\Models\TransactionDetail;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Account extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}

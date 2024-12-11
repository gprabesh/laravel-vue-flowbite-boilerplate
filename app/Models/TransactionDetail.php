<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'from_account_id',
        'to_account_id',
        'transaction_category_id',
        'type',
        'amount',
        'date',
        'notes',
    ];

    public function fromAccount() {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function transctionCategory()
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }
}

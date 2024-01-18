<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

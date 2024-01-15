<?php

namespace App\Models;

use App\Enums\AccountCategory;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'category',
        'balance'
    ];

    // protected function category() : Attribute {
    //     return Attribute::make(
    //         get: fn (string $value) => AccountCategory::fromValue(intval($value))->key,
    //     );
    // }
}

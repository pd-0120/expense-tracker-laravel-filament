<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionCategory extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'transaction_category_id',
    ];

    public function parentcategory() {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id', 'id');
    }

    public function ChildCategories()
    {
        return $this->hasMany(TransactionCategory::class, 'transaction_category_id', 'id');
    }
}

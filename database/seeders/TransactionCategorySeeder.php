<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\TransactionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(range(0,10) as $data) {
            $type = CategoryType::getRandomValue();

            TransactionCategory::create([
                'name' => fake()->city,
                'type' => $type
            ]);
        }
    }
}

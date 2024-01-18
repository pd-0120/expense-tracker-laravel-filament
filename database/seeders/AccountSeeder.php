<?php

namespace Database\Seeders;

use App\Enums\AccountCategory;
use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(0, 10) as $data) {
            $type = AccountCategory::getRandomValue();

            Account::create([
                'name' => fake()->city,
                'category' => $type
            ]);
        }
    }
}

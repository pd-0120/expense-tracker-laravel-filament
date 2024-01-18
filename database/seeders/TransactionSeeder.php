<?php

namespace Database\Seeders;

use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $user = User::first();
            $accounts = Account::count();
            $categories = TransactionCategory::count();
            $transactionData = [];

            foreach(range(1,5000) as $data) {
                $transactionData[] = [
                    'user_id' => $user->id,
                    'from_account_id' => rand(1, $accounts),
                    'to_account_id' => rand(1, $accounts),
                    'transaction_category_id' => rand(1, $categories),
                    'type' => TransactionTypeEnum::getRandomValue(),
                    'amount' => rand(50, 5000),
                    'date' => Carbon::now()->subDays(rand(0, 356)),
                    'notes' => ''
                ];
            }
            Transaction::insert($transactionData);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            dd($th, $accounts, $categories);
        }
    }
}

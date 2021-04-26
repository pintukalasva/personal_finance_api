<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transactions')->truncate();
        DB::table('transactions')->insert([
            ['date' => Carbon::now(), 'amount' => 100, 'category_id' => 1, 'account_id' => 1, 'transaction_type' => "income", 'description' => "Salary", 'user_id' => 1],
            ['date' => Carbon::now()->addDay(1), 'amount' => 100, 'category_id' => 2, 'account_id' => 2, 'transaction_type' => "expense", 'description' => "PNB", 'user_id' => 1],
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('accounts')->truncate();
        DB::table('accounts')->insert([
        	['name' => 'Cash' , 'user_id' => 1],
        	['name' => 'Bank', 'user_id' => 1],
        ]);
    }
}

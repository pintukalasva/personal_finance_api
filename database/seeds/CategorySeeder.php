<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('categories')->truncate();
        DB::table('categories')->insert([
        	['name' => 'Salary', 'type' => "income", 'user_id' => 1],
        	['name' => 'EMI', 'type' => "expense", 'user_id' => 1],
            ['name' => 'Freelancing', 'type' => "income", 'user_id' => 1],
            ['name' => 'Electricity Bill', 'type' => "expense", 'user_id' => 1],
        ]);
    }
}

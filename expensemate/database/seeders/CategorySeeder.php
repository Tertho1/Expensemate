<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            ['name' => 'Salary', 'type' => 'income', 'user_id' => null],
            ['name' => 'Freelancing', 'type' => 'income', 'user_id' => null],
            ['name' => 'Food', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Transport', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Shopping', 'type' => 'expense', 'user_id' => null],
            ['name' => 'Bills', 'type' => 'expense', 'user_id' => null],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        Category::insert([
            ['name' => 'Food', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Travel', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Salary', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Shopping', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bills', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Transport', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Entertainment', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Healthcare', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Freelancing', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Investment', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Education', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Utilities', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rent', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gift', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Business', 'user_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}

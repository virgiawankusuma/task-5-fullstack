<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(2)->create();
        Category::factory(5)->create();
        \App\Models\Article::factory(10)->create();


        // \App\Models\User::factory(10)->create();
    }
}

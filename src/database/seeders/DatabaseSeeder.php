<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\ConditionSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ConditionSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
        ]);
    }
}

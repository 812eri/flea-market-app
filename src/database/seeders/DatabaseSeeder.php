<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\ConditionSeeder;
use Database\Seeders\CategorySeeder;

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
            ConditionSeeder::class,
            CategorySeeder::class,
        ]);
    }
}

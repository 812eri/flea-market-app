<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'description' => $this->faker->realText(50),
            'price' => $this->faker->numberBetween(100, 10000),
            'brand_name' =>$this->faker->word(),
            'category_id' => Category::factory(),
            'condition_id' => Condition::factory(),
            'image_url' =>$this->faker->imageUrl(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->text(30),
            'content' => $this->faker->text(),
            'image' => $this->faker->imageUrl(200, 200),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'user_id' => $this->faker->numberBetween(1, User::count()),
            'category_id' => $this->faker->unique()->numberBetween(1, Category::count()),
        ];
    }
}

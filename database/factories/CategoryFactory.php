<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'agency_id' => $this->faker->numberBetween(1, 5),
            'name' => ucfirst($name),
            'slug' => str()->slug($name),
            'description' => $this->faker->sentence(),
        ];
    }
}

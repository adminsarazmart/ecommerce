<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement(['fas fa-laptop', 'fas fa-tshirt', 'fas fa-home', 'fas fa-book', 'fas fa-futbol', 'fas fa-paint-brush']),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(0, 100),
            'is_active' => true,
            'display_mode' => fake()->randomElement(['products', 'subcategories', 'both']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => true]);
    }

    public function parent(): static
    {
        return $this->state(fn (array $attributes) => ['parent_id' => null]);
    }

    public function childOf(Category $parent): static
    {
        return $this->state(fn (array $attributes) => ['parent_id' => $parent->id]);
    }
}

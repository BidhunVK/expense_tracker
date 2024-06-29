<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Catogory>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Utilities', 'Rent', 'Transportation', 'Travel', 'Personal Care',
            'Food', 'Insurance', 'Entertainment', 'Education', 'Debt Payments',
            'Savings', 'Contingency Expenses'
        ];
        return [
            'category' => $this->faker->unique()->randomElement($categories),
        ];
    }
}

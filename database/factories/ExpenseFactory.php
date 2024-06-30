<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Category;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        $dateOfExpense = $this->faker->dateTimeBetween($startDate, $endDate);

        $category = Category::inRandomOrder()->first();

        return [
            'user_id' => 3,
            'category_id' => $category->id,
            'amount' => $this->faker->randomFloat(2, 10, 1000), 
            'description' => $this->faker->sentence,
            'date_of_expense' => $dateOfExpense,
        ];
    }
}

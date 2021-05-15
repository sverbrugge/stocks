<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            'stock_id' => Stock::factory(),
            'price' => $this->faker->randomFloat(4, 0, 9999),
            'quoted_at' => $this->faker->dateTime,
        ];
    }
}

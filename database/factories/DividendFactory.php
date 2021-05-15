<?php

namespace Database\Factories;

use App\Models\Dividend;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class DividendFactory extends Factory
{
    protected $model = Dividend::class;

    public function definition(): array
    {
        return [
            'stock_id' => Stock::factory(),
            'transacted_at' => $this->faker->dateTime,
            'price' => $this->faker->randomFloat(4, 0, 9999),
        ];
    }
}

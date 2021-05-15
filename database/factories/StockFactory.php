<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Exchange;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        return [
            'ticker' => Str::upper($this->faker->unique()->lexify('???')),
            'name' => $this->faker->unique()->lexify('??????'),
            'currency_id' => Currency::factory(),
            'exchange_id' => Exchange::factory(),
            'active' => $this->faker->boolean(80),
        ];
    }
}

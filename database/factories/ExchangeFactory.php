<?php

namespace Database\Factories;

use App\Models\Exchange;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExchangeFactory extends Factory
{
    protected $model = Exchange::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'timezone' => $this->faker->timezone,
            'trading_from' => $this->faker->time(),
            'trading_to' => $this->faker->time(),
        ];
    }
}

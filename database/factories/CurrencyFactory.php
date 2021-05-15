<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->currencyCode,
            'symbol' => $this->faker->unique()->randomLetter,
            'default' => null,
        ];
    }
}

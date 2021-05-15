<?php

namespace Database\Factories;

use App\Models\Share;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShareFactory extends Factory
{
    protected $model = Share::class;

    public function definition(): array
    {
        return [
            'stock_id' => Stock::factory(),
            'parent_id' => null,
            'transacted_at' => $this->faker->dateTime,
            'amount' => $this->faker->numberBetween(1, 9999),
            'price' => $this->faker->randomFloat(4, 0, 9999),
            'exchange_rate' => $this->faker->randomFloat(4, 0, 9999),
            'active' => $this->faker->boolean(90),
        ];
    }
}

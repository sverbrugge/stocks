<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Dividend;
use App\Models\Exchange;
use App\Models\Quote;
use App\Models\Share;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);

        $currencies = Currency::factory()
            ->count(3)
            ->create();

        $exchanges = Exchange::factory()
            ->count(5)
            ->create();

        for ($i = 0; $i < 25; $i++) {
            /** @var Currency $currency */
            $currency = $currencies->random(1)->first();

            /** @var Exchange $exchange */
            $exchange = $exchanges->random(1)->first();

            Stock::factory()
                ->has(
                    Dividend::factory()
                        ->count(rand(0, 25))
                )
                ->has(
                    Share::factory()
                        ->count(rand(0, 3))
                )
                ->has(
                    Quote::factory()
                        ->count(rand(25, 100))
                )
                ->create(
                    [
                        'currency_id' => $currency->id,
                        'exchange_id' => $exchange->id,
                    ]
                );
        }
    }
}

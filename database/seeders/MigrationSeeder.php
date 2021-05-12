<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MigrationSeeder extends Seeder
{
    public function run(): void
    {
        if (!env('DB_DATABASE_MIGRATION')) {
            $this->command->error('DB_DATABASE_MIGRATION is not set or empty');
        }

        $this->call(
            [
                Migration\CurrenciesSeeder::class,
                Migration\ExchangesSeeder::class,
                Migration\StocksSeeder::class,
                Migration\SharesSeeder::class,
                Migration\DividendsSeeder::class,
                Migration\QuotesSeeder::class,
            ]
        );
    }
}

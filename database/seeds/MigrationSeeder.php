<?php

use Illuminate\Database\Seeder;

class MigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		if( !($this->db = env('DB_DATABASE_MIGRATION')) )
			return $this->command->error('DB_DATABASE_MIGRATION is not set or empty');

        $this->call([
			Migration\CurrenciesSeeder::class,
			Migration\ExchangesSeeder::class,
			Migration\StocksSeeder::class,
			Migration\SharesSeeder::class,
			Migration\DividendsSeeder::class,
			Migration\QuotesSeeder::class,
		]);
    }
}

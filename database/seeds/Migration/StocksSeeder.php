<?php

namespace Migration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$db = env('DB_DATABASE_MIGRATION');
		
        $stocks = DB::table("{$db}.stocks")->get();
        
        foreach( $stocks as $stock )
			\App\Stock::firstOrCreate([
				'id'			=> $stock->id,
				'ticker'		=> $stock->ticker,
				'name'			=> $stock->name,
				'currency_id'	=> $stock->currencies_id,
				'exchange_id'	=> $stock->exchanges_id,
			]);
    }
}

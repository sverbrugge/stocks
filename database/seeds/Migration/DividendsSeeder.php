<?php

namespace Migration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DividendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$db = env('DB_DATABASE_MIGRATION');
		
        $dividends = DB::table("{$db}.dividend")->get();
        
        foreach( $dividends as $dividend )
			\App\Dividend::firstOrCreate([
				'id'			=> $dividend->id,
				'stock_id'		=> $dividend->stocks_id,
				'transacted_at'	=> $dividend->transaction_date,
				'price'			=> $dividend->price,
			]);
    }
}

<?php

namespace Migration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$db = env('DB_DATABASE_MIGRATION');
		
        $currencies = DB::table("{$db}.currencies")->get();
        
        foreach( $currencies as $currency )
			\App\Currency::firstOrCreate( (array) $currency );
    }
}

<?php

namespace Migration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExchangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$db = env('DB_DATABASE_MIGRATION');
		
        $exchanges = DB::table("{$db}.exchanges")->get();
        
        foreach( $exchanges as $exchange )
			\App\Exchange::firstOrCreate([
				'id'			=> $exchange->id,
				'name'			=> $exchange->name,
				'timezone'		=> config('app.timezone', 'UTC'),
				'trading_from'	=> $exchange->trading_from,
				'trading_to'	=> $exchange->trading_till,
			]);
    }
}

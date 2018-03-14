<?php

namespace Migration;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SharesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$db = env('DB_DATABASE_MIGRATION');
		
        $shares = DB::table("{$db}.shares")->get();
        
        foreach( $shares as $share )
			\App\Share::firstOrCreate([
				'id'			=> $share->id,
				'stock_id'		=> $share->stocks_id,
				'parent_id'		=> $share->parent_id ? $share->parent_id : null,
				'transacted_at'	=> $share->transaction_date,
				'amount'		=> $share->amount,
				'price'			=> $share->price,
				'exchange_rate'	=> $share->exchange_rate,
			]);
    }
}

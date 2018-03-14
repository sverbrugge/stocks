<?php

namespace Migration;

use Illuminate\Database\Seeder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class QuotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$db = env('DB_DATABASE_MIGRATION');
		
        $quotes = DB::table("{$db}.history")->get();
        
        $progressbar = $this->command->getOutput()->createProgressBar( $quotes->count() );
        $progressbar->setFormat('very_verbose');
        
        foreach( $quotes as $quote )
        {
			try
			{
				\App\Quote::create([
					'id'			=> $quote->id,
					'stock_id'		=> $quote->stocks_id,
					'quoted_at'		=> $quote->timestamp,
					'price'			=> $quote->price,
				]);
			}
			catch(QueryException $e)
			{
				$this->command->error('Quotes table has already been populated');
				break;
			}
			
			$progressbar->advance();
		}
    }
}

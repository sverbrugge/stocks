<?php

namespace Database\Seeders\Migration;

use App\Models\Quote;
use Illuminate\Database\Seeder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class QuotesSeeder extends Seeder
{
    public function run(): void
    {
        $db = env('DB_DATABASE_MIGRATION');

        $quotes = DB::table("{$db}.history")->get();

        $progressbar = $this->command->getOutput()->createProgressBar($quotes->count());
        $progressbar->setFormat('very_verbose');

        foreach ($quotes as $quote) {
            try {
                Quote::create(
                    [
                        'id' => $quote->id,
                        'stock_id' => $quote->stocks_id,
                        'quoted_at' => $quote->timestamp,
                        'price' => $quote->price,
                    ]
                );
            } catch (QueryException $e) {
                $this->command->error('Quotes table has already been populated');
                break;
            }

            $progressbar->advance();
        }
    }
}

<?php

namespace Database\Seeders\Migration;

use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StocksSeeder extends Seeder
{
    public function run(): void
    {
        $db = env('DB_DATABASE_MIGRATION');

        $stocks = DB::table("{$db}.stocks")->get();

        foreach ($stocks as $stock) {
            Stock::firstOrCreate(
                [
                    'id' => $stock->id,
                    'ticker' => $stock->ticker,
                    'name' => $stock->name,
                    'currency_id' => $stock->currencies_id,
                    'exchange_id' => $stock->exchanges_id,
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders\Migration;

use App\Models\Dividend;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DividendsSeeder extends Seeder
{
    public function run(): void
    {
        $db = env('DB_DATABASE_MIGRATION');

        $dividends = DB::table("{$db}.dividend")->get();

        foreach ($dividends as $dividend) {
            Dividend::firstOrCreate(
                [
                    'id' => $dividend->id,
                    'stock_id' => $dividend->stocks_id,
                    'transacted_at' => $dividend->transaction_date,
                    'price' => $dividend->price,
                ]
            );
        }
    }
}

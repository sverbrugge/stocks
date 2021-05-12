<?php

namespace Database\Seeders\Migration;

use App\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
{
    public function run(): void
    {
        $db = env('DB_DATABASE_MIGRATION');

        $currencies = DB::table("{$db}.currencies")->get();

        foreach ($currencies as $currency) {
            Currency::firstOrCreate((array)$currency);
        }
    }
}

<?php

namespace Database\Seeders\Migration;

use App\Models\Exchange;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExchangesSeeder extends Seeder
{
    public function run(): void
    {
        $db = env('DB_DATABASE_MIGRATION');

        $exchanges = DB::table("{$db}.exchanges")->get();

        foreach ($exchanges as $exchange) {
            Exchange::firstOrCreate(
                [
                    'id' => $exchange->id,
                    'name' => $exchange->name,
                    'timezone' => config('app.timezone', 'UTC'),
                    'trading_from' => $exchange->trading_from,
                    'trading_to' => $exchange->trading_till,
                ]
            );
        }
    }
}

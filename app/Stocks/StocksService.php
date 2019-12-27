<?php

namespace App\Stocks;

use AlphaVantage\Api\TimeSeries;
use AlphaVantage\Client;
use AlphaVantage\Options;
use App\Quote;
use App\Stock;
use DateTimeZone;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class StocksService
{
    const API_CALL_DELAY = 15;
    const TTL = 86400;

    protected $client;

    public function __construct()
    {
        $this->client = new Client((new Options())->setApiKey(config('alphavantage.key')));
    }

    public function getAllTickers()
    {
        return Stock::all();
    }

    public function getTicker(string $ticker)
    {
        return Stock::where('ticker', $ticker)->firstOrFail();
    }

    /**
     * @param Collection $stocks
     * @param bool $full
     * @return array
     */
    public function update(Collection $stocks, bool $full = false)
    {
        $outputType = $full ? TimeSeries::OUTPUT_TYPE_FULL : TimeSeries::OUTPUT_TYPE_COMPACT;
        $updated = [];

        $stocks->each(function ($stock) use ($outputType, &$updated) {
            $data = $this->getTimeSeriesDaily($stock, $outputType);
            $updated[$stock->ticker] = [
                'count' => $data->count(),
                'date_min' => $data->keys()->sort()->first(),
                'date_max' => $data->keys()->sort()->last(),
            ];

            $data->each(function ($day, $date) use ($stock, &$updatedDate) {
                $date = new Carbon($date, new DateTimeZone($stock->exchange->timezone));

                $dataPoints = [
                    '1. open' => $date->clone()->setTimeFromTimeString($stock->exchange->trading_from),
                    '4. close' => $date->clone()->setTimeFromTimeString($stock->exchange->trading_to),
                ];

                foreach ($dataPoints as $key => $timestamp) {
                    if (empty($day[$key]) && !floatval($day[$key])) {
                        continue;
                    }

                    $existingStock = Quote::where('stock_id', $stock->id)->where('quoted_at', $timestamp)->first();
                    if ($existingStock) {
                        $existingStock->update([
                            'price' => $day[$key],
                            'quoted_at' => $timestamp,
                        ]);
                        $existingStock->saveOrFail();
                        continue;
                    }

                    Quote::create([
                        'stock_id' => $stock->id,
                        'price' => $day[$key],
                        'quoted_at' => $timestamp,
                    ]);
                }
            });

            sleep(self::API_CALL_DELAY);
        });

        return $updated;
    }

    protected function getTimeSeriesDaily(Stock $stock, string $outputType)
    {
        return Cache::remember($stock->ticker. $outputType, self::TTL, function () use ($stock, $outputType) {
            return collect($this->client->timeSeries()->daily($stock->ticker, $outputType)['Time Series (Daily)']);
        });
    }
}
<?php

namespace App\Stocks;

use AlphaVantage\Api\TimeSeries;
use AlphaVantage\Client;
use AlphaVantage\Exception\RuntimeException;
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
    const TTL_TIMESERIES = 86400;
    const TTL_QUOTE = 3600;

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
     * @param bool $delay
     * @return \Generator
     * @throws \Exception
     */
    public function update(Collection $stocks, bool $delay = true)
    {
        foreach ($stocks as $stock) {
            $timestamp = new Carbon('now', new DateTimeZone($stock->exchange->timezone));

            $tradingFrom = $timestamp->setTimeFromTimeString($stock->exchange->trading_from);
            $tradingTo = $timestamp->clone()->setTimeFromTimeString($stock->exchange->trading_to);

            $result = [
                'ticker' => $stock->ticker,
                'price' => null,
            ];

            if ($tradingFrom->isPast() && $tradingTo->isFuture()) {
                $data = $this->getQuote($stock, $delay);

                if (!empty($data)) {
                    $latestTradingDay = new Carbon($data['07. latest trading day'], new DateTimeZone($stock->exchange->timezone));

                    if ($latestTradingDay->isToday()) {
                        $result['price'] = (float)$data['05. price'];
                        $this->addQuote($stock, new Carbon(), $result['price']);
                    }
                }
            } else {
                logger('Stock exchange currently not trading: ' . $stock->ticker);
            }

            yield $result;
        }
    }

    /**
     * @param Collection $stocks
     * @param bool $full
     * @param bool $delay
     * @return \Generator
     * @throws \Throwable
     */
    public function updateHistorical(Collection $stocks, bool $full = false, bool $delay = true)
    {
        $outputType = $full ? TimeSeries::OUTPUT_TYPE_FULL : TimeSeries::OUTPUT_TYPE_COMPACT;
        $updated = [];

        foreach ($stocks as $stock) {
            $data = $this->getTimeSeriesDaily($stock, $outputType, $delay);
            $updated[$stock->ticker] = [
                'ticker' => $stock->ticker,
                'count' => $data->count(),
                'date_min' => $data->keys()->sort()->first(),
                'date_max' => $data->keys()->sort()->last(),
            ];

            foreach ($data as $date => $day) {
                $date = new Carbon($date, new DateTimeZone($stock->exchange->timezone));

                $dataPoints = [
                    '1. open' => $date->clone()->setTimeFromTimeString($stock->exchange->trading_from),
                    '4. close' => $date->clone()->setTimeFromTimeString($stock->exchange->trading_to),
                ];

                foreach ($dataPoints as $key => $timestamp) {
                    if (empty($day[$key]) && !floatval($day[$key])) {
                        continue;
                    }

                    if ($timestamp->isFuture()) {
                        continue;
                    }

                    $this->addQuote($stock, $timestamp, (float)$day[$key]);
                }
            };

            yield $updated[$stock->ticker];
        };
    }

    /**
     * @param Stock $stock
     * @param Carbon $timestamp
     * @param float $price
     * @throws \Throwable
     */
    protected function addQuote(Stock $stock, Carbon $timestamp, float $price)
    {
        $timestamp->timezone = config('app.timezone');

        $existingStock = Quote::where('stock_id', $stock->id)->where('quoted_at', $timestamp)->first();
        if ($existingStock) {
            $existingStock->update([
                'price' => $price,
                'quoted_at' => $timestamp,
            ]);
            $existingStock->saveOrFail();
            return;
        }

        Quote::create([
            'stock_id' => $stock->id,
            'price' => $price,
            'quoted_at' => $timestamp,
        ]);
    }

    protected function getTimeSeriesDaily(Stock $stock, string $outputType, bool $delay = true)
    {
        return Cache::remember($stock->ticker . $outputType, self::TTL_TIMESERIES, function () use ($stock, $outputType, $delay) {
            $result = collect();

            try {
                $result = collect($this->client->timeSeries()->daily($stock->ticker, $outputType)['Time Series (Daily)']);
            } catch (RuntimeException $e) {
                logger('Error getting daily time series for "' . $stock->ticker . '"');
            }

            if ($delay) {
                sleep(self::API_CALL_DELAY);
            }

            return $result;
        });
    }

    protected function getQuote(Stock $stock, bool $delay = true)
    {
        return Cache::remember($stock->ticker . 'quote', self::TTL_QUOTE, function () use ($stock, $delay) {
            $result = [];

            try {
                $result = $this->client->timeSeries()->globalQuote($stock->ticker)['Global Quote'];
            } catch (RuntimeException $e) {
                logger('Error getting quote for "' . $stock->ticker . '"');
            }

            if ($delay) {
                sleep(self::API_CALL_DELAY);
            }

            return $result;
        });
    }
}
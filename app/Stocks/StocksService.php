<?php

namespace App\Stocks;

use AlphaVantage\Api\TimeSeries;
use AlphaVantage\Client;
use AlphaVantage\Options;
use App\Exceptions\StockNotTradingException;
use App\Exchange;
use App\Quote;
use App\Stock;
use Carbon\CarbonInterval;
use DateInterval;
use DateTimeZone;
use Exception;
use Generator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Throwable;

class StocksService
{
    const API_CALL_DELAY = 15;
    const TTL_TIME_SERIES = 86400;
    const TTL_QUOTE = 3600;

    protected $client;

    public function __construct()
    {
        $this->client = new Client((new Options())->setApiKey(config('alphavantage.key')));
    }

    public function getAllTickers()
    {
        return Stock::active()->get();
    }

    public function getTicker(string $ticker)
    {
        return Stock::ticker($ticker)->active()->firstOrFail();
    }

    /**
     * @param Collection $exchanges
     * @param Carbon $day
     * @param DateInterval|null $interval
     * @return Collection
     */
    public function getExchangeReport(Collection $exchanges, Carbon $day, DateInterval $interval = null) {
        $compareTo = $day->clone()->subtract($interval ?? CarbonInterval::day(1));

        return $exchanges->map(function (Exchange $exchange) use ($day, $compareTo) {
            return $exchange->stocks()->active()->get()->map(function (Stock $stock) use ($day, $compareTo) {
                $firstQuote = $stock->quotes()->whereBetween('quoted_at', [$compareTo, $day])->limit(1)->orderBy('quoted_at', 'asc')->firstOrFail();
                $lastQuote = $stock->quotes()->whereBetween('quoted_at', [$compareTo, $day])->limit(1)->orderBy('quoted_at', 'desc')->firstOrFail();

                return collect([
                    'ticker' => $stock->ticker,
                    'last_quote' => $lastQuote->price,
                    'difference' => $lastQuote->price - $firstQuote->price,
                    'percentage' => (($lastQuote->price - $firstQuote->price) / $lastQuote->price) * 100,
                ]);
            });
        })->flatten(1);
    }

    /**
     * @param Collection $stocks
     * @param bool $delay
     * @param bool $force
     * @return Generator
     * @throws Throwable
     */
    public function update(Collection $stocks, bool $delay = true, bool $force = false)
    {
        foreach ($stocks as $stock) {
            $timestamp = new Carbon('now', new DateTimeZone($stock->exchange->timezone));

            $tradingFrom = $timestamp->setTimeFromTimeString($stock->exchange->trading_from);
            $tradingTo = $timestamp->clone()->setTimeFromTimeString($stock->exchange->trading_to);

            $result = [
                'ticker' => $stock->ticker,
                'price' => null,
                'exception' => null,
            ];

            try {
                if ($force || ($tradingFrom->isPast() && $tradingTo->isFuture())) {
                    $data = $this->getQuote($stock, $delay);

                    if (!empty($data)) {
                        $latestTradingDay = new Carbon($data['07. latest trading day'], new DateTimeZone($stock->exchange->timezone));

                        if ($latestTradingDay->isToday()) {
                            $result['price'] = (float)$data['05. price'];
                            $this->addQuote($stock, new Carbon(), $result['price']);
                        }
                    }
                } else {
                    throw new StockNotTradingException();
                }
            } catch (Exception $e) {
                $result['exception'] = $e;
            }

            yield $result;
        }
    }

    /**
     * @param Collection $stocks
     * @param bool $full
     * @param bool $delay
     * @return Generator
     * @throws Throwable
     */
    public function updateHistorical(Collection $stocks, bool $full = false, bool $delay = true)
    {
        $outputType = $full ? TimeSeries::OUTPUT_TYPE_FULL : TimeSeries::OUTPUT_TYPE_COMPACT;
        $updated = [];

        foreach ($stocks as $stock) {
            $updated[$stock->ticker] = [
                'ticker' => $stock->ticker,
                'count' => null,
                'date_min' => null,
                'date_max' => null,
                'exception' => null,
            ];

            try {
                $data = $this->getTimeSeriesDaily($stock, $outputType, $delay);

                $updated[$stock->ticker]['count'] = $data->count();
                $updated[$stock->ticker]['date_min'] = $data->keys()->sort()->first();
                $updated[$stock->ticker]['date_max'] = $data->keys()->sort()->last();

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
            } catch (Exception $e) {
                $updated[$stock->ticker]['exception'] = $e;
            }

            yield $updated[$stock->ticker];
        };
    }

    /**
     * @param Stock $stock
     * @param Carbon $timestamp
     * @param float $price
     * @throws Throwable
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

    /**
     * Get historical data from API
     *
     * @param Stock $stock
     * @param string $outputType
     * @param bool $delay
     * @return Collection
     * @throws Exception
     */
    protected function getTimeSeriesDaily(Stock $stock, string $outputType, bool $delay = true)
    {
        return Cache::remember($stock->ticker . $outputType, self::TTL_TIME_SERIES, function () use ($stock, $outputType, $delay) {
            try {
                $result = collect($this->client->timeSeries()->daily($stock->ticker, $outputType)['Time Series (Daily)']);
            } finally {
                if ($delay) {
                    sleep(self::API_CALL_DELAY);
                }
            }

            return $result;
        });
    }

    /**
     * Get current quote from API
     *
     * @param Stock $stock
     * @param bool $delay
     * @return array
     * @throws Exception
     */
    protected function getQuote(Stock $stock, bool $delay = true)
    {
        return Cache::remember($stock->ticker . 'quote', self::TTL_QUOTE, function () use ($stock, $delay) {
            try {
                $result = $this->client->timeSeries()->globalQuote($stock->ticker)['Global Quote'];
            } finally {
                if ($delay) {
                    sleep(self::API_CALL_DELAY);
                }
            }

            return $result;
        });
    }
}
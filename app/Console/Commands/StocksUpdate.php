<?php

namespace App\Console\Commands;

use App\Stocks\StocksService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Throwable;

class StocksUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:update {--all} {--force} {symbol?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stock quote with latest data, if available';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Throwable
     */
    public function handle(): int
    {
        $stocksService = new StocksService();

        if (!$this->option('all') && !$this->argument('symbol')) {
            $this->warn('You need to provide a ticker symbol.');
            return 1;
        }

        try {
            $stocks = $this->option('all') ? $stocksService->getAllTickers() : new Collection([$stocksService->getTicker($this->argument('symbol'))]);
        } catch (ModelNotFoundException $e) {
            $this->warn('Ticker symbol not found or not active: "' . $this->argument('symbol') . '"');
            return 2;
        }

        $progressBar = $this->output->createProgressBar($stocks->count());
        $progressBar->setFormat('very_verbose');
        $progressBar->start();

        $updatedStocks = $stocksService->update($stocks, true, $this->option('force'));
        $processedStocks = [];

        foreach ($updatedStocks as $data) {
            if ($data['exception'] instanceof Exception) {
                $data['exception'] = $data['exception']->getMessage();
            }

            $processedStocks[] = $data;
            $progressBar->advance();
        }

        $progressBar->clear();

        $headers = [
            'ticker' => 'Symbol',
            'price' => 'Price',
            'exception' => 'Exception',
        ];
        $this->table($headers, $processedStocks);

        return 0;
    }
}

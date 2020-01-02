<?php

namespace App\Console\Commands;

use App\Stocks\StocksService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class StocksUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:update {--all} {symbol?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stock quote with latest data, if available';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $stocksService = new StocksService();

        if (!$this->option('all') && !$this->argument('symbol')) {
            $this->warn('You need to provide a ticker symbol.');
            return null;
        }

        try {
            $stocks = $this->option('all') ? $stocksService->getAllTickers() : new Collection([$stocksService->getTicker($this->argument('symbol'))]);
        } catch (ModelNotFoundException $e) {
            $this->warn('Ticker symbol not found: "' . $this->argument('symbol') . '"');
            return null;
        }

        $progressBar = $this->output->createProgressBar($stocks->count());
        $progressBar->setFormat('very_verbose');
        $progressBar->start();

        $updatedStocks = $stocksService->update($stocks);
        $processedStocks = [];

        foreach ($updatedStocks as $data) {
            $processedStocks[] = $data;
            $progressBar->advance();
        }

        $progressBar->clear();

        $headers = [
            'ticker' => 'Symbol',
            'price' => 'Price',
        ];
        $this->table($headers, $processedStocks);

        return $processedStocks;
    }
}

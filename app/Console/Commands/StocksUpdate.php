<?php

namespace App\Console\Commands;

use App\Stocks\StocksService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class StocksUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:update {--all} {--full} {symbol?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stocks data';

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
     */
    public function handle()
    {
        $stocksService = new StocksService();
        $full = $this->option('full');

        if ($full) {
            $this->info(trans('Using full historical data'));
        }

        $stocks = $this->option('all') ? $stocksService->getAllTickers() : new Collection([$stocksService->getTicker($this->argument('symbol'))]);
        $updatedStocks = $stocksService->update($stocks, $full);

        foreach ($updatedStocks as $ticker => $data) {
            $this->info($ticker . ': ' . $data['count'] . ' (' . $data['date_min'] . ' to ' . $data['date_max'] . ')');
        }

        return $updatedStocks;
    }
}

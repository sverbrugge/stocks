<?php

namespace App\Console\Commands;

use App\Exchange;
use App\Mail\StocksReport as StocksReportMail;
use App\Stocks\StocksService;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Laminas\Text\Table\Column;
use Laminas\Text\Table\Row;
use Laminas\Text\Table\Table;

class StocksReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:report {--email} {exchange_id*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a report based on latest stock quotes';

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
        $exchangeIds = $this->argument('exchange_id');
        $exchanges = Exchange::with('stocks')->whereIn('id', $exchangeIds)->get();

        $stocksService = new StocksService();

        try {
            $report = $stocksService->getExchangeReport($exchanges, Carbon::now(), CarbonInterval::week());
        } catch (ModelNotFoundException $e) {
            $this->warn('No quote(s) found');
            return;
        }

        if ($this->option('email')) {
            Mail::to(env('MAIL_REPORT_TO', 'www-data'))->send(new StocksReportMail($report));
            $this->info('E-mail sent');
            return;
        }

        echo $this->renderTable($report);
        return;
    }

    /**
     * @param Collection $report
     * @return string
     */
    public function renderTable(Collection $report)
    {
        /** @var Collection $columnWidths */
        $columnWidths = $report->first()->keys()->map(function ($key) use ($report) {
            return $report->max(function ($item) use ($key) {
                $item[$key] = is_numeric($item[$key]) ? sprintf('%.4f', $item[$key]) : (string)$item[$key];
                return max(strlen($item[$key]) + 2, strlen($key)) + 2;
            });
        });

        $table = new Table([
            'columnWidths' => $columnWidths->all(),
            'padding' => [1, 1],
            'AutoSeparate' => Table::AUTO_SEPARATE_HEADER,
        ]);

        $table->appendRow($report->first()->keys()->all());

        foreach ($report as $item) {
            $row = new Row();

            foreach ($item as $col) {
                $row->appendColumn(new Column($col, is_numeric($col) ? Column::ALIGN_RIGHT : Column::ALIGN_LEFT));
            }

            $table->appendRow($row);
        }

        return $table->render();
    }
}

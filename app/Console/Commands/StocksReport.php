<?php

namespace App\Console\Commands;

use App\Exchange;
use App\Mail\StocksReport as StocksReportMail;
use App\Stocks\StocksService;
use Exception;
use Illuminate\Console\Command;
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
     * @throws Exception
     */
    public function handle()
    {
        $exchangeIds = $this->argument('exchange_id');
        $exchanges = Exchange::with('stocks')->whereIn('id', $exchangeIds)->get();

        $stocksService = new StocksService();

        $to = Carbon::today();
        $from = new Carbon($to->isWeekend() ? 'last Monday' : ($to->isMonday() ? 'last Friday': 'yesterday'));

        $report = $stocksService->getExchangeReport($exchanges, $from, $to);
        $plainTextTable = $this->renderTable($report);

        if ($this->option('email')) {
            Mail::to(env('MAIL_REPORT_TO', 'www-data'))->send(new StocksReportMail($report, $plainTextTable));
            $this->info('E-mail sent');
            return;
        }

        echo $plainTextTable;
        return;
    }

    /**
     * @param Collection $report
     * @return string
     */
    public function renderTable(Collection $report)
    {
        $headers = [
            'ticker' => trans('Ticker'),
            'date_first' => trans('From'),
            'date_last' => trans('To'),
            'quote' => trans('Quote'),
            'difference' => trans('Difference'),
            'percentage' => trans('Percentage'),
        ];

        $body = $report->map(function ($row) {
            return [
                'ticker' => $row['stock']->ticker,
                'date_first' => $row['first_quote_date']->format('d-m-Y H:i'),
                'date_last' => $row['last_quote_date']->format('d-m-Y H:i'),
                'quote' => sprintf('%0.4f', $row['last_quote']),
                'difference' => sprintf('%0.4f', $row['difference']),
                'percentage' => sprintf('%0.2f', $row['percentage']),
            ];
        });

        $columnWidths = collect($headers)->map(function ($header) {
            return strlen($header) + 2;
        })->all();

        $body->each(function ($row) use (&$columnWidths) {
            foreach ($row as $key => $col) {
                $columnWidths[$key] = max($columnWidths[$key], strlen($col) + 2);
            }
        });

        $table = new Table([
            'columnWidths' => array_values($columnWidths),
            'padding' => [1, 1],
            'AutoSeparate' => Table::AUTO_SEPARATE_HEADER,
        ]);

        $table->appendRow(array_values($headers));

        $body->each(function ($item) use (&$table) {
            $row = new Row();

            foreach ($item as $cell) {
                $row->appendColumn(new Column($cell, is_numeric($cell) ? Column::ALIGN_RIGHT : Column::ALIGN_LEFT));
            }

            $table->appendRow($row);
        });

        return $table->render();
    }
}

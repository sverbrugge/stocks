<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class StocksReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Collection
     */
    private $report;
    /**
     * @var string
     */
    private $text;

    /**
     * Create a new message instance.
     *
     * @param Collection $report
     * @param string $text
     */
    public function __construct(Collection $report, string $text = '')
    {
        $this->report = $report;
        $this->text = $text ?? trans('This message is HTML only. There is no plaintext available.');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('email.report')
            ->text('email.report-plain')
            ->with([
                'report' => $this->report,
                'text' => $this->text,
            ]);
    }
}

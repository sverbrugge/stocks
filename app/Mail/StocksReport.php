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
     * Create a new message instance.
     *
     * @param Collection $report
     */
    public function __construct(Collection $report)
    {
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.report')->with('report', $this->report);
    }
}

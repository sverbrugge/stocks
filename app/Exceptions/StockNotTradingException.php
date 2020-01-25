<?php

namespace App\Exceptions;

class StockNotTradingException extends StocksException
{
    protected $message = 'This stock is currently not trading';
}
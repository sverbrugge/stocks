<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Exchange;
use App\Currency;

class Stock extends Model
{
    protected $with = [
        'exchange',
        'currency',
    ];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}

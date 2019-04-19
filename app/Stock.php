<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'ticker',
        'name',
        'currency_id',
        'exchange_id'
    ];

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

    public function shares() {
        return $this->hasMany(Share::class);
    }

    public function getActiveSharesAttribute() {
        return $this->shares()->whereNull('parent_id')->get();
    }

    public function quotes() {
        return $this->hasMany(Quote::class);
    }

    public function getCurrentQuoteAttribute() {
        return $this->quotes()->orderBy('quoted_at', 'DESC')->first();
    }
}

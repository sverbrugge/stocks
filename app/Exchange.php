<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $fillable = [
        'name',
        'timezone',
        'trading_from',
        'trading_to',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}

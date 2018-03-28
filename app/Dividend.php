<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Dividend extends Model
{
    protected $dates = [
        'transacted_at',
    ];

    protected $fillable = [
        'stock_id',
        'transacted_at',
        'price',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sortByTransactionDate', function (Builder $builder) {
            $builder->orderBy('transacted_at', 'DESC');
        });
    }

    public function stock() {
        return $this->belongsTo(Stock::class);
    }
}

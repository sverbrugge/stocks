<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Quote extends Model
{
    protected $dates = [
        'quoted_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByDateQuoted', function (Builder $builder) {
            $builder->orderBy('quoted_at', 'ASC');
        });
    }

    public function stock() {
        return $this->belongsTo(Stock::class);
    }
}

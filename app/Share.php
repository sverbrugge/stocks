<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\Stock;

class Share extends Model
{
    protected $dates = [
        'transacted_at',
    ];

    protected $fillable = [
        'parent_id',
        'stock_id',
        'transacted_at',
        'amount',
        'price',
        'exchange_rate',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sortByTransactionDate', function (Builder $builder) {
            $builder->orderBy('transacted_at', 'DESC');
        });
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}

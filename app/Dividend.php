<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Dividend
 *
 * @property int $id
 * @property int $stock_id
 * @property \Illuminate\Support\Carbon $transacted_at
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Stock $stock
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dividend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dividend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dividend query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dividend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dividend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dividend wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dividend whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dividend whereTransactedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dividend whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

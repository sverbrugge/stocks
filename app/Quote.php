<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Quote
 *
 * @property int $id
 * @property int $stock_id
 * @property float $price
 * @property \Illuminate\Support\Carbon $quoted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Stock $stock
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quote query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quote wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quote whereQuotedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quote whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Quote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

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
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Stock $stock
 * @method static Builder|Dividend newModelQuery()
 * @method static Builder|Dividend newQuery()
 * @method static Builder|Dividend query()
 * @method static Builder|Dividend whereCreatedAt($value)
 * @method static Builder|Dividend whereId($value)
 * @method static Builder|Dividend wherePrice($value)
 * @method static Builder|Dividend whereStockId($value)
 * @method static Builder|Dividend whereTransactedAt($value)
 * @method static Builder|Dividend whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
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

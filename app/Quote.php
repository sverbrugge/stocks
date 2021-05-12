<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Quote
 *
 * @property int $id
 * @property int $stock_id
 * @property string $price
 * @property \Illuminate\Support\Carbon $quoted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Stock $stock
 * @method static Builder|Quote newModelQuery()
 * @method static Builder|Quote newQuery()
 * @method static Builder|Quote query()
 * @method static Builder|Quote whereCreatedAt($value)
 * @method static Builder|Quote whereId($value)
 * @method static Builder|Quote wherePrice($value)
 * @method static Builder|Quote whereQuotedAt($value)
 * @method static Builder|Quote whereStockId($value)
 * @method static Builder|Quote whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Quote extends Model
{
    protected $dates = [
        'quoted_at',
    ];

    protected $fillable = [
        'stock_id',
        'price',
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

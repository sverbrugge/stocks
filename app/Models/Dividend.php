<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Dividend
 *
 * @property int $id
 * @property int $stock_id
 * @property \Illuminate\Support\Carbon $transacted_at
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Stock $stock
 * @method static \Database\Factories\DividendFactory factory(...$parameters)
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
    use HasFactory;

    protected $dates = [
        'transacted_at',
    ];

    protected $fillable = [
        'stock_id',
        'transacted_at',
        'price',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(
            'sortByTransactionDate',
            function (Builder $builder) {
                $builder->orderBy('transacted_at', 'DESC');
            }
        );
    }

    /**
     * @return BelongsTo|Stock
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}

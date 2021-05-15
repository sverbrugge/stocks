<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Quote
 *
 * @property int $id
 * @property int $stock_id
 * @property string $price
 * @property \Illuminate\Support\Carbon $quoted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Stock $stock
 * @method static \Database\Factories\QuoteFactory factory(...$parameters)
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
    use HasFactory;

    protected $dates = [
        'quoted_at',
    ];

    protected $fillable = [
        'stock_id',
        'price',
        'quoted_at',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(
            'orderByDateQuoted',
            function (Builder $builder) {
                $builder->orderBy('quoted_at', 'ASC');
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

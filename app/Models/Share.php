<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Share
 *
 * @property int $id
 * @property int $stock_id
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $transacted_at
 * @property int $amount
 * @property string $price
 * @property string $exchange_rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $active
 * @property-read \Illuminate\Database\Eloquent\Collection|Share[] $children
 * @property-read int|null $children_count
 * @property-read string $all_shares_sold
 * @property-read string $color_class
 * @property-read string $gain
 * @property-read string $gain_color_class
 * @property-read string $percent_gain
 * @property-read string $sold_gain
 * @property-read string $sold_gain_percent
 * @property-read string $total_price
 * @property-read Share|null $parent
 * @property-read \App\Models\Stock $stock
 * @method static Builder|Share active(bool $active = true)
 * @method static \Database\Factories\ShareFactory factory(...$parameters)
 * @method static Builder|Share newModelQuery()
 * @method static Builder|Share newQuery()
 * @method static Builder|Share query()
 * @method static Builder|Share sold()
 * @method static Builder|Share whereActive($value)
 * @method static Builder|Share whereAmount($value)
 * @method static Builder|Share whereCreatedAt($value)
 * @method static Builder|Share whereExchangeRate($value)
 * @method static Builder|Share whereId($value)
 * @method static Builder|Share whereParentId($value)
 * @method static Builder|Share wherePrice($value)
 * @method static Builder|Share whereStockId($value)
 * @method static Builder|Share whereTransactedAt($value)
 * @method static Builder|Share whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Share extends Model
{
    use HasFactory;

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
        'active',
    ];

    protected $with = [
        'stock',
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
     * @return HasMany|Share
     */
    public function children(): HasMany
    {
        return $this->hasMany(Share::class, 'parent_id');
    }

    /**
     * @return BelongsTo|Share
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Share::class, 'parent_id');
    }

    /**
     * @return BelongsTo|Stock
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function scopeActive(Builder $query, bool $active = true): Builder
    {
        return $query->where('active', $active);
    }

    public function scopeSold(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id');
    }

    public function getAllSharesSoldAttribute(): string
    {
        $parent = $this->parent
            ?: $this;

        return $parent->children->sum('amount') === $parent->amount;
    }

    public function getColorClassAttribute(): string
    {
        if (!$this->stock->current_quote) {
            return '';
        }

        switch ($this->price <=> $this->stock->current_quote->price) {
            case -1:
                return 'success';

            case 1:
                return 'danger';
        }

        return '';
    }

    public function getGainAttribute(): string
    {
        return ($this->stock->current_quote->price <=> $this->price ? '+' : '') . sprintf(
                '%.2f',
                $this->stock->current_quote->price - $this->price
            );
    }

    public function getGainColorClassAttribute(): string
    {
        switch ($this->soldGain <=> 0) {
            case 1:
                return 'success';

            case -1:
                return 'danger';
        }

        return '';
    }

    public function getPercentGainAttribute(): string
    {
        $currentQuote = $this->stock->current_quote;

        if (!$currentQuote) {
            return '';
        }

        return sprintf('%.2f%%', ($currentQuote->price / $this->price * 100) - 100);
    }

    public function getSoldGainAttribute(): string
    {
        return $this->parent
            ? sprintf('%.4f', $this->total_price - $this->parent->total_price)
            : '';
    }

    public function getSoldGainPercentAttribute(): string
    {
        return $this->parent
            ? sprintf('%.2f', ($this->total_price - $this->parent->total_price) / $this->parent->total_price * 100)
            : '';
    }

    public function getTotalPriceAttribute(): string
    {
        return number_format($this->amount * $this->price, 4);
    }
}

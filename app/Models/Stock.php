<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * App\Models\Stock
 *
 * @property int $id
 * @property string|null $ticker
 * @property string $name
 * @property int $currency_id
 * @property int $exchange_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $active
 * @property-read \App\Models\Currency $currency
 * @property-read \App\Models\Exchange $exchange
 * @property-read \Illuminate\Support\Collection|\App\Models\Share[] $active_shares
 * @property-read \App\Models\Quote|null $current_quote
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Quote[] $quotes
 * @property-read int|null $quotes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Share[] $shares
 * @property-read int|null $shares_count
 * @method static Builder|Stock active(bool $active = true)
 * @method static \Database\Factories\StockFactory factory(...$parameters)
 * @method static Builder|Stock newModelQuery()
 * @method static Builder|Stock newQuery()
 * @method static Builder|Stock query()
 * @method static Builder|Stock ticker(string $ticker)
 * @method static Builder|Stock whereActive($value)
 * @method static Builder|Stock whereCreatedAt($value)
 * @method static Builder|Stock whereCurrencyId($value)
 * @method static Builder|Stock whereExchangeId($value)
 * @method static Builder|Stock whereId($value)
 * @method static Builder|Stock whereName($value)
 * @method static Builder|Stock whereTicker($value)
 * @method static Builder|Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticker',
        'name',
        'currency_id',
        'exchange_id',
        'active',
    ];

    protected $with = [
        'exchange',
        'currency',
    ];

    /**
     * @return BelongsTo|Currency
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return BelongsTo|Exchange
     */
    public function exchange(): BelongsTo
    {
        return $this->belongsTo(Exchange::class);
    }

    /**
     * @return HasMany|Share
     */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    /**
     * @return HasMany|Quote
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }


    public function scopeActive(Builder $query, bool $active = true): Builder
    {
        return $query->where('active', $active);
    }

    public function scopeTicker(Builder $query, string $ticker): Builder
    {
        return $query->where('ticker', $ticker);
    }

    /**
     * @return Collection|Share[]
     */
    public function getActiveSharesAttribute(): Collection
    {
        return $this->shares()
            ->whereNull('parent_id')
            ->get();
    }

    public function getCurrentQuoteAttribute(): ?Quote
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->quotes()
            ->orderBy('quoted_at', 'DESC')
            ->first();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Stock
 *
 * @property int $id
 * @property string|null $ticker
 * @property string $name
 * @property int $currency_id
 * @property int $exchange_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $active
 * @property-read \App\Currency $currency
 * @property-read \App\Exchange $exchange
 * @property-read mixed $active_shares
 * @property-read mixed $current_quote
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Quote[] $quotes
 * @property-read int|null $quotes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Share[] $shares
 * @property-read int|null $shares_count
 * @method static Builder|Stock active(bool $active = true)
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
    protected $fillable = [
        'ticker',
        'name',
        'currency_id',
        'exchange_id',
        'active'
    ];

    protected $with = [
        'exchange',
        'currency',
    ];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function shares() {
        return $this->hasMany(Share::class);
    }

    public function getActiveSharesAttribute() {
        return $this->shares()->whereNull('parent_id')->get();
    }

    public function quotes() {
        return $this->hasMany(Quote::class);
    }

    public function getCurrentQuoteAttribute() {
        return $this->quotes()->orderBy('quoted_at', 'DESC')->first();
    }

    public function scopeActive(Builder $query, bool $active = true)
    {
        return $query->where('active', $active);
    }

    public function scopeTicker(Builder $query, string $ticker)
    {
        return $query->where('ticker', $ticker);
    }
}

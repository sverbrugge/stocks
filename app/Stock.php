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
 * @property-read \App\Currency $currency
 * @property-read \App\Exchange $exchange
 * @property-read mixed $active_shares
 * @property-read mixed $current_quote
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Quote[] $quotes
 * @property-read int|null $quotes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Share[] $shares
 * @property-read int|null $shares_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereExchangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereTicker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock active($active = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Stock whereActive($value)
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
}

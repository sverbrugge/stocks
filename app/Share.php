<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Share
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
 * @property-read mixed $all_shares_sold
 * @property-read mixed $color_class
 * @property-read mixed $gain
 * @property-read mixed $gain_color_class
 * @property-read mixed $percent_gain
 * @property-read mixed $sold_gain
 * @property-read mixed $sold_gain_percent
 * @property-read mixed $total_price
 * @property-read Share|null $parent
 * @property-read \App\Stock $stock
 * @method static Builder|Share active(bool $active = true)
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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sortByTransactionDate', function (Builder $builder) {
            $builder->orderBy('transacted_at', 'DESC');
        });
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function getTotalPriceAttribute()
    {
        return number_format($this->amount * $this->price, 4);
    }

    public function getGainAttribute()
    {
        return ($this->stock->currentQuote->price <=> $this->price ? '+' : '') . sprintf('%.2f', $this->stock->currentQuote->price - $this->price);
    }

    public function getSoldGainAttribute()
    {
        return $this->parent ? sprintf('%.4f', $this->totalPrice - $this->parent->totalPrice) : '';
    }

    public function getSoldGainPercentAttribute()
    {
        return $this->parent ? sprintf('%.2f', ($this->totalPrice - $this->parent->totalPrice) / $this->parent->totalPrice * 100) : '';
    }

    public function getPercentGainAttribute()
    {
        $currentQuote = $this->stock->currentQuote;

        if (!$currentQuote) {
            return '';
        }

        return sprintf('%.2f%%', ($currentQuote->price / $this->price * 100) - 100);
    }

    public function getColorClassAttribute()
    {
        if (!$this->stock->currentQuote) {
            return '';
        }

        switch ($this->price <=> $this->stock->currentQuote->price) {
            case -1:
                return 'success';

            case 1:
                return 'danger';
        }

        return '';
    }

    public function getGainColorClassAttribute()
    {
        switch ($this->soldGain <=> 0) {
            case 1:
                return 'success';

            case -1:
                return 'danger';
        }

        return '';
    }

    public function getAllSharesSoldAttribute()
    {
        $parent = $this->parent ?: $this;
        return $parent->children->sum('amount') === $parent->amount;
    }

    public function scopeSold(Builder $query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeActive(Builder $query, bool $active = true)
    {
        return $query->where('active', $active);
    }
}

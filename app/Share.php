<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function getGainAttribute() {
        return ($this->stock->currentQuote->price <=> $this->price ? '+' : '') . sprintf('%.2f', $this->stock->currentQuote->price - $this->price);
    }

    public function getSoldGainAttribute() {
        return $this->parent ? sprintf('%.4f', $this->totalPrice - $this->parent->totalPrice) : '';
    }

    public function getSoldGainPercentAttribute() {
        return $this->parent ? sprintf('%.2f', ($this->totalPrice - $this->parent->totalPrice) / $this->parent->totalPrice * 100) : '';
    }

    public function getPercentGainAttribute() {
        return sprintf('%.2f%%', ($this->stock->currentQuote->price / $this->price * 100) - 100);
    }

    public function getColorClassAttribute()
    {
        switch ($this->price <=> $this->stock->currentQuote->price)
        {
            case -1:
                return 'success';

            case 1:
                return 'danger';
        }

        return '';
    }

    public function getGainColorClassAttribute()
    {
        switch ($this->soldGain <=> 0)
        {
            case 1:
                return 'success';

            case -1:
                return 'danger';
        }

        return '';
    }

    public function scopeSold(Builder $query) {
        return $query->whereNotNull('parent_id');
    }
}
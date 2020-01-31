<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Exchange
 *
 * @property int $id
 * @property string $name
 * @property string $timezone
 * @property string $trading_from
 * @property string $trading_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Stock[] $stocks
 * @property-read int|null $stocks_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereTradingFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereTradingTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exchange whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Exchange extends Model
{
    protected $fillable = [
        'name',
        'timezone',
        'trading_from',
        'trading_to',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Stock
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Exchange
 *
 * @property int $id
 * @property string $name
 * @property string $timezone
 * @property string $trading_from
 * @property string $trading_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Stock[] $stocks
 * @property-read int|null $stocks_count
 * @method static \Database\Factories\ExchangeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange query()
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereTradingFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereTradingTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exchange whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'timezone',
        'trading_from',
        'trading_to',
    ];

    /**
     * @return HasMany|Stock
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}

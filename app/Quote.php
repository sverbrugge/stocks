<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $dates = [
        'quoted_at',
    ];

    public function stock() {
        return $this->belongsTo(Stock::class);
    }
}

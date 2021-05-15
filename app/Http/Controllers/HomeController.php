<?php

namespace App\Http\Controllers;

use App\Models\Share;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
    }

    public function index(): Renderable
    {
        $shares = Share::active()->with('children')->whereNull('parent_id')->get();

        $totals = $shares
            ->groupBy(
                [
                    'stock.currency.code',
                    function ($share) {
                        if ($share->allSharesSold) {
                            return 'sold';
                        }

                        if ($share->percentGain < 0) {
                            return 'loss';
                        }

                        return 'gain';
                    },
                ]
            )
            ->map(
                function (Collection $currencyGroup) {
                    return $currencyGroup->map(
                        function (Collection $gainLossGroup) {
                            return $gainLossGroup->sum(
                                function (Share $share) {
                                    return ($share->amount - $share->children->sum(
                                                'amount'
                                            )) * ($share->stock->currentQuote->price - $share->price);
                                }
                            );
                        }
                    );
                }
            );

        return view('home')
            ->with(
                [
                    'shares' => $shares->groupBy('stock.exchange.name'),
                    'totals' => $totals,
                ]
            );
    }
}

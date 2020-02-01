<?php

namespace App\Http\Controllers;

use App\Share;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $shares = Share::active()->with('children')->whereNull('parent_id')->get();
        $totals = $shares->groupBy([
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
        ])->map(function (Collection $currencyGroup) {
            return $currencyGroup->map(function (Collection $gainLossGroup) {
                return $gainLossGroup->sum(function (Share $share) {
                    return ($share->amount - $share->children->sum('amount')) * ($share->stock->currentQuote->price - $share->price);
                });
            });
        });

        return view('home')->with([
            'shares' => $shares,
            'totals' => $totals,
        ]);
    }
}

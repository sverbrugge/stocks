<?php

namespace App\Http\Controllers;

use App\Stock;

class GraphController extends Controller
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

    public function index(Stock $stock) {
        return view('graph')->with('stock', $stock);
    }

    public function stock(Stock $stock) {
        return $stock->quotes->map(function ($item) {
            return [
                $item->quoted_at->valueOf(),
                (float)$item->price,
            ];
        });
    }
}

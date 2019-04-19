<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $this->middleware('auth');
    }

    public function index(Stock $stock) {
        return view('graph')->with('stock', $stock);
    }

    public function stock(Stock $stock) {
        return $stock->quotes->map(function ($item, $key) {
            return [
                $item->quoted_at->valueOf(),
                (float)$item->price,
            ];
        });
    }
}

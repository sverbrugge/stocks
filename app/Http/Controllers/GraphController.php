<?php

namespace App\Http\Controllers;

use App\Stock;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class GraphController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa'])
            ->except('stock');

        $this->middleware('auth:api')
            ->only('stock');
    }

    public function index(Stock $stock): Renderable
    {
        return view('graph')
            ->with('stock', $stock);
    }

    public function stock(Stock $stock): Collection
    {
        return $stock->quotes->map(
            function ($item) {
                return [
                    $item->quoted_at->valueOf(),
                    (float)$item->price,
                ];
            }
        );
    }
}

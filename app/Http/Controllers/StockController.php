<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

use App\Stock;
use App\Currency;
use App\Exchange;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('stocks.index')->with('stocks', Stock::paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stocks.create')->with([
            'currencies'    => Currency::all(),
            'exchanges'     => Exchange::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticker'        => [ 'required', Rule::unique('stocks') ],
            'name'          => [ 'required', Rule::unique('stocks') ],
            'currency_id'   => [ 'required', 'exists:currencies,id' ],
            'exchange_id'   => [ 'required', 'exists:exchanges,id' ],
        ]);

        $stock = Stock::create( $request->only('ticker', 'name', 'currency_id', 'exchange_id'));

        return redirect()->route('stocks.index')->with([
            'success' => __('The stock ":name (:ticker)" has been added.', [ 'name' => $stock->name, 'ticker' => $stock->ticker]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Stock $stock)
    {
        return view('stocks.show')->with([
            'stock'             => $stock,
            'confirmDeletion'   => $request->input('delete') == 'confirm',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        return view('stocks.edit')->with([
            'stock'         => $stock,
            'currencies'    => Currency::all(),
            'exchanges'     => Exchange::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'ticker'        => [ 'required', Rule::unique('stocks')->ignore($stock->id) ],
            'name'          => [ 'required', Rule::unique('stocks')->ignore($stock->id) ],
            'currency_id'   => [ 'required', 'exists:currencies,id' ],
            'exchange_id'   => [ 'required', 'exists:exchanges,id' ],
        ]);

        $stock->update($request->only('ticker', 'name', 'currency_id', 'exchange_id'));

        return redirect()->route('stocks.show', [ 'stocks' => $stock ])->with([
            'success' => __('Your changes have been saved.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        try {
            $stock->delete();
        }
        catch (QueryException $e)
        {
            return redirect()->route('stocks.show', [ 'stocks' => $stock])->with('warning', __('This item could not be deleted.'));
        }

        return redirect()->route('stocks.index')->with('info', __('You have succesfully deleted ":name".', [ 'name' => $stock->name ]));
    }
}

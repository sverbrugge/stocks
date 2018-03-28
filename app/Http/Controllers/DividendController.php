<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

use App\Dividend;
use App\Stock;

class DividendController extends Controller
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
        return view('dividends.index')->with([
            'dividends' => Dividend::with(['stock'])->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dividends.create')->with([
            'stocks'    => Stock::all(),
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
            'stock_id'      => [ 'required', 'exists:stocks,id' ],
            'transacted_at' => [ 'required', 'date_format:Y-m-d' ],
            'price'         => [ 'required', 'numeric' ],
        ]);

        $dividend = Dividend::create($request->only('stock_id', 'transacted_at', 'price'));

        return redirect()->route('dividends.index')->with([
            'success' => __('The dividend ":name (:date)" has been added.', [ 'name' => $dividend->stock->name, 'date' => $dividend->transacted_at->formatLocalized('%d %B %Y')]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dividend  $dividend
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Dividend $dividend)
    {
        return view('dividends.show')->with([
            'dividend'          => $dividend,
            'confirmDeletion'   => $request->input('delete') == 'confirm',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dividend  $dividend
     * @return \Illuminate\Http\Response
     */
    public function edit(Dividend $dividend)
    {
        return view('dividends.edit')->with([
            'dividend'         => $dividend,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dividend  $dividend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dividend $dividend)
    {
        $request->validate([
            'transacted_at' => [ 'required', 'date_format:Y-m-d' ],
            'price'         => [ 'required', 'numeric' ],
        ]);

        $dividend->update($request->only('transacted_at', 'price'));

        return redirect()->route('dividends.show', [ 'dividends' => $dividend ])->with([
            'success' => __('Your changes have been saved.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dividend  $dividend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dividend $dividend)
    {
        try {
            $dividend->delete();
        }
        catch (QueryException $e)
        {
            return redirect()->route('dividends.show', [ 'dividends' => $dividend])->with('warning', __('This item could not be deleted.'));
        }

        return redirect()->route('dividends.index')->with([
            'info' => __('You have succesfully deleted ":name (:date)".', [ 'name' => $dividend->stock->name, 'date' => $dividend->transacted_at->formatLocalized('%d %B %Y')]),
        ]);
    }
}

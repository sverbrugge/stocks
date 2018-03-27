<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

use App\Share;
use App\Stock;

class ShareController extends Controller
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
        return view('shares.index')->with([
            'shares' => Share::with(['stock', 'children'])->whereNull('parent_id')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shares.create')->with([
            'stocks' => Stock::all(),
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
            'amount'        => [ 'required', 'integer' ],
            'price'         => [ 'required', 'numeric' ],
            'exchange_rate' => [ 'required', 'numeric' ],
        ]);

        $share = Share::create( $request->only('stock_id', 'transacted_at', 'amount', 'price', 'exchange_rate'));

        return redirect()->route('shares.index')->with([
            'success' => __('The share ":name (:date)" has been added.', [ 'name' => $share->stock->name, 'date' => $share->transacted_at->formatLocalized('%d %B %Y')]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Share $share)
    {
        return view('shares.show')->with([
            'share'             => $share,
            'confirmDeletion'   => $request->input('delete') == 'confirm',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function edit(Share $share)
    {
        return view('shares.edit')->with([
            'share'         => $share,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Share $share)
    {
        $request->validate([
            'transacted_at' => [ 'required', 'date_format:Y-m-d' ],
            'amount'        => [ 'required', 'integer' ],
            'price'         => [ 'required', 'numeric' ],
            'exchange_rate' => [ 'required', 'numeric' ],
        ]);

        $share->update($request->only('transacted_at', 'amount', 'price'));

        return redirect()->route('shares.show', [ 'shares' => $share ])->with([
            'success' => __('Your changes have been saved.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function destroy(Share $share)
    {
        try {
            $share->delete();
        }
        catch (QueryException $e)
        {
            return redirect()->route('shares.show', [ 'shares' => $share])->with('warning', __('This item could not be deleted.'));
        }

        return redirect()->route('shares.index')->with([
            'info' => __('You have succesfully deleted ":name (:date)".', [ 'name' => $share->stock->name, 'date' => $share->transacted_at->formatLocalized('%d %B %Y')]),
        ]);
    }
}

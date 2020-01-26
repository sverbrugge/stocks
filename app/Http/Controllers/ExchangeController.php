<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

use App\Exchange;

class ExchangeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('exchanges.index')->with('exchanges', Exchange::paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('exchanges.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => [ 'required', Rule::unique('exchanges') ],
            'timezone'      => [ 'required', 'timezone' ],
            'trading_from'  => [ 'required', 'regex:/^\d\d:\d\d(:\d\d)?$/' ],
            'trading_to'    => [ 'required', 'regex:/^\d\d:\d\d(:\d\d)?$/', 'different:trading_from' ],
        ]);

        $values = $request->only('name', 'timezone', 'trading_from', 'trading_to');

        $exchange = Exchange::create($values);

        return redirect()->route('exchanges.index')->with([
            'success' => __('The exchange ":name" has been added.', [ 'name' => $exchange->name ]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exchange  $exchange
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Request $request, Exchange $exchange)
    {
        return view('exchanges.show')->with([
            'exchange'          => $exchange,
            'confirmDeletion'   => $request->input('delete') == 'confirm',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Exchange  $exchange
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Exchange $exchange)
    {
        return view('exchanges.edit')->with([
            'exchange'      => $exchange,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exchange  $exchange
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Exchange $exchange)
    {
        $request->validate([
            'name'          => [ 'required', Rule::unique('exchanges')->ignore($exchange->id) ],
            'timezone'      => [ 'required', 'timezone' ],
            'trading_from'  => [ 'required', 'regex:/^\d\d:\d\d(:\d\d)?$/' ],
            'trading_to'    => [ 'required', 'regex:/^\d\d:\d\d(:\d\d)?$/', 'different:trading_from' ],
        ]);

        $values = $request->only('name', 'timezone', 'trading_from', 'trading_to');

        $exchange->update($values);

        return redirect()->route('exchanges.show', [ 'exchanges' => $exchange ])->with([
            'success' => __('Your changes have been saved.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Exchange $exchange
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Exchange $exchange)
    {
        try {
            $exchange->delete();
        }
        catch (QueryException $e)
        {
            return redirect()->route('exchanges.show', [ 'exchanges' => $exchange])->with('warning', __('This item could not be deleted.'));
        }

        return redirect()->route('exchanges.index')->with('info', __('You have succesfully deleted ":name".', [ 'name' => $exchange->name ]));
    }
}

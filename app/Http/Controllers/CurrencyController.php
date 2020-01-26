<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

use App\Currency;

class CurrencyController extends Controller
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
        return view('currencies.index')->with('currencies', Currency::paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('currencies.create');
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
            'code'      => [ 'required', 'size:3', Rule::unique('currencies') ],
            'symbol'    => [ 'required', 'size:1' ],
            'default'   => [ 'nullable' ],
        ]);

        $currency = Currency::create($request->only('code', 'symbol', 'default'));

        return redirect()->route('currencies.index')->with([
            'success' => __('The currency ":code" has been added.', [ 'code' => $currency->code ]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currency  $currency
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Request $request, Currency $currency)
    {
        return view('currencies.show')->with([
            'currency'          => $currency,
            'confirmDeletion'   => $request->input('delete') == 'confirm',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Currency $currency)
    {
        return view('currencies.edit')->with([
            'currency'      => $currency,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'code'      => [ 'required', 'size:3', Rule::unique('currencies')->ignore($currency->id) ],
            'symbol'    => [ 'required', 'size:1' ],
            'default'   => [ 'nullable', 'boolean' ],
        ]);

        $currency->update($request->only('code', 'symbol', 'default'));

        return redirect()->route('currencies.show', [ 'currencies' => $currency ])->with([
            'success' => __('Your changes have been saved.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Currency $currency
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Currency $currency)
    {
        try {
            $currency->delete();
        }
        catch (QueryException $e)
        {
            return redirect()->route('currencies.show', [ 'currencies' => $currency])->with('warning', __('This item could not be deleted.'));
        }

        return redirect()->route('currencies.index')->with('info', __('You have succesfully deleted ":name".', [ 'name' => $currency->name ]));
    }
}

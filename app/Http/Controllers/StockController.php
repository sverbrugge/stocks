<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
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
        $this->middleware(['auth', '2fa']);
    }

    public function index(Request $request): Renderable
    {
        $inactive = $request->get('inactive');
        return view('stocks.index')->with(
            [
                'stocks' => Stock::active(!$inactive)->paginate(),
                'inactive' => $inactive,
            ]
        );
    }

    public function create(): Renderable
    {
        return view('stocks.create')
            ->with(
                [
                    'currencies' => Currency::all(),
                    'exchanges' => Exchange::all(),
                ]
            );
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'ticker' => ['required', Rule::unique('stocks')],
                'name' => ['required', Rule::unique('stocks')],
                'currency_id' => ['required', 'exists:currencies,id'],
                'exchange_id' => ['required', 'exists:exchanges,id'],
            ]
        );

        $stock = Stock::create($request->only('ticker', 'name', 'currency_id', 'exchange_id'));

        return redirect()
            ->route('stocks.index')
            ->with(
                [
                    'success' => __(
                        'The stock ":name (:ticker)" has been added.',
                        ['name' => $stock->name, 'ticker' => $stock->ticker]
                    ),
                ]
            );
    }

    public function show(Request $request, Stock $stock): Renderable
    {
        return view('stocks.show')
            ->with(
                [
                    'stock' => $stock,
                    'confirmDeletion' => $request->input('delete') == 'confirm',
                ]
            );
    }

    public function edit(Stock $stock): Renderable
    {
        return view('stocks.edit')
            ->with(
                [
                    'stock' => $stock,
                    'currencies' => Currency::all(),
                    'exchanges' => Exchange::all(),
                ]
            );
    }

    public function update(Request $request, Stock $stock): RedirectResponse
    {
        $request->validate(
            [
                'ticker' => ['required', Rule::unique('stocks')->ignore($stock->id)],
                'name' => ['required', Rule::unique('stocks')->ignore($stock->id)],
                'currency_id' => ['required', 'exists:currencies,id'],
                'exchange_id' => ['required', 'exists:exchanges,id'],
                'active' => ['required', 'boolean'],
            ]
        );

        $stock->update($request->only('ticker', 'name', 'currency_id', 'exchange_id', 'active'));

        return redirect()->route('stocks.show', ['stocks' => $stock])->with(
            [
                'success' => __('Your changes have been saved.'),
            ]
        );
    }

    public function destroy(Stock $stock): RedirectResponse
    {
        try {
            $stock->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('stocks.show', ['stocks' => $stock])
                ->with(
                    'warning',
                    __('This item could not be deleted.')
                );
        }

        return redirect()
            ->route('stocks.index')
            ->with(
                'info',
                __(
                    'You have succesfully deleted ":name".',
                    ['name' => $stock->name]
                )
            );
    }
}

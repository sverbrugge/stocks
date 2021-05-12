<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Dividend;
use App\Stock;

class DividendController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
    }

    public function index(): Renderable
    {
        return view('dividends.index')
            ->with(
                [
                    'dividends' => Dividend::with(['stock'])->paginate(),
                ]
            );
    }

    public function create(): Renderable
    {
        return view('dividends.create')
            ->with(
                [
                    'stocks' => Stock::all(),
                ]
            );
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'stock_id' => ['required', 'exists:stocks,id'],
                'transacted_at' => ['required', 'date_format:Y-m-d'],
                'price' => ['required', 'numeric'],
            ]
        );

        $dividend = Dividend::create($request->only('stock_id', 'transacted_at', 'price'));

        return redirect()->route('dividends.index')
            ->with(
                [
                    'success' => __(
                        'The dividend ":name (:date)" has been added.',
                        [
                            'name' => $dividend->stock->name,
                            'date' => $dividend->transacted_at->formatLocalized('%d %B %Y'),
                        ]
                    ),
                ]
            );
    }

    public function show(Request $request, Dividend $dividend): Renderable
    {
        return view('dividends.show')
            ->with(
                [
                    'dividend' => $dividend,
                    'confirmDeletion' => $request->input('delete') == 'confirm',
                ]
            );
    }

    public function edit(Dividend $dividend): Renderable
    {
        return view('dividends.edit')
            ->with(
                [
                    'dividend' => $dividend,
                ]
            );
    }

    public function update(Request $request, Dividend $dividend): RedirectResponse
    {
        $request->validate(
            [
                'transacted_at' => ['required', 'date_format:Y-m-d'],
                'price' => ['required', 'numeric'],
            ]
        );

        $dividend->update($request->only('transacted_at', 'price'));

        return redirect()
            ->route('dividends.show', ['dividends' => $dividend])
            ->with(
                [
                    'success' => __('Your changes have been saved.'),
                ]
            );
    }

    public function destroy(Dividend $dividend): RedirectResponse
    {
        try {
            $dividend->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('dividends.show', ['dividends' => $dividend])
                ->with(
                    'warning',
                    __(
                        'This item could not be deleted.'
                    )
                );
        }

        return redirect()
            ->route('dividends.index')
            ->with(
                [
                    'info' => __(
                        'You have succesfully deleted ":name (:date)".',
                        [
                            'name' => $dividend->stock->name,
                            'date' => $dividend->transacted_at->formatLocalized('%d %B %Y'),
                        ]
                    ),
                ]
            );
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
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

    public function index(): Renderable
    {
        return view('currencies.index')
            ->with(
                [
                    'currencies' => Currency::paginate(),
                ]
            );
    }

    public function create(): Renderable
    {
        return view('currencies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'code' => ['required', 'size:3', Rule::unique('currencies')],
                'symbol' => ['required', 'size:1'],
                'default' => ['nullable'],
            ]
        );

        $currency = Currency::create($request->only('code', 'symbol', 'default'));

        return redirect()
            ->route('currencies.index')
            ->with(
                [
                    'success' => __('The currency ":code" has been added.', ['code' => $currency->code]),
                ]
            );
    }

    public function show(Request $request, Currency $currency): Renderable
    {
        return view('currencies.show')
            ->with(
                [
                    'currency' => $currency,
                    'confirmDeletion' => $request->input('delete') == 'confirm',
                ]
            );
    }

    public function edit(Currency $currency): Renderable
    {
        return view('currencies.edit')
            ->with(
                [
                    'currency' => $currency,
                ]
            );
    }

    public function update(Request $request, Currency $currency): RedirectResponse
    {
        $request->validate(
            [
                'code' => ['required', 'size:3', Rule::unique('currencies')->ignore($currency->id)],
                'symbol' => ['required', 'size:1'],
                'default' => ['nullable', 'boolean'],
            ]
        );

        $currency->update($request->only('code', 'symbol', 'default'));

        return redirect()
            ->route('currencies.show', ['currencies' => $currency])
            ->with(
                [
                    'success' => __('Your changes have been saved.'),
                ]
            );
    }

    public function destroy(Currency $currency): RedirectResponse
    {
        try {
            $currency->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('currencies.show', ['currencies' => $currency])
                ->with(
                    'warning',
                    __(
                        'This item could not be deleted.'
                    )
                );
        }

        return redirect()
            ->route('currencies.index')
            ->with(
                'info',
                __(
                    'You have succesfully deleted ":name".',
                    ['name' => $currency->name]
                )
            );
    }
}

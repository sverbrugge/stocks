<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
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

    public function index(): Renderable
    {
        return view('exchanges.index')
            ->with(
                [
                    'exchanges' => Exchange::paginate(),
                ]
            );
    }

    public function create(): Renderable
    {
        return view('exchanges.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', Rule::unique('exchanges')],
                'timezone' => ['required', 'timezone'],
                'trading_from' => ['required', 'regex:/^\d\d:\d\d(:\d\d)?$/'],
                'trading_to' => ['required', 'regex:/^\d\d:\d\d(:\d\d)?$/', 'different:trading_from'],
            ]
        );

        $values = $request->only('name', 'timezone', 'trading_from', 'trading_to');

        $exchange = Exchange::create($values);

        return redirect()
            ->route('exchanges.index')
            ->with(
                [
                    'success' => __('The exchange ":name" has been added.', ['name' => $exchange->name]),
                ]
            );
    }

    public function show(Request $request, Exchange $exchange): Renderable
    {
        return view('exchanges.show')
            ->with(
                [
                    'exchange' => $exchange,
                    'confirmDeletion' => $request->input('delete') == 'confirm',
                ]
            );
    }

    public function edit(Exchange $exchange): Renderable
    {
        return view('exchanges.edit')
            ->with(
                [
                    'exchange' => $exchange,
                ]
            );
    }

    public function update(Request $request, Exchange $exchange): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', Rule::unique('exchanges')->ignore($exchange->id)],
                'timezone' => ['required', 'timezone'],
                'trading_from' => ['required', 'regex:/^\d\d:\d\d(:\d\d)?$/'],
                'trading_to' => ['required', 'regex:/^\d\d:\d\d(:\d\d)?$/', 'different:trading_from'],
            ]
        );

        $values = $request->only('name', 'timezone', 'trading_from', 'trading_to');

        $exchange->update($values);

        return redirect()
            ->route('exchanges.show', ['exchanges' => $exchange])
            ->with(
                [
                    'success' => __('Your changes have been saved.'),
                ]
            );
    }

    public function destroy(Exchange $exchange): RedirectResponse
    {
        try {
            $exchange->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('exchanges.show', ['exchanges' => $exchange])
                ->with(
                    'warning',
                    __(
                        'This item could not be deleted.'
                    )
                );
        }

        return redirect()
            ->route('exchanges.index')
            ->with(
                'info',
                __(
                    'You have succesfully deleted ":name".',
                    ['name' => $exchange->name]
                )
            );
    }
}

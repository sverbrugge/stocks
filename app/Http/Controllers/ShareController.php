<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Models\Share;
use App\Models\Stock;

class ShareController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
    }

    public function index(Request $request): Renderable
    {
        $inactive = $request->get('inactive');

        return view('shares.index')
            ->with(
                [
                    'shares' => Share::active(!$inactive)->with(['stock', 'children'])->whereNull(
                        'parent_id'
                    )->paginate(),
                    'inactive' => $inactive,
                ]
            );
    }

    public function create(Request $request): Renderable
    {
        $request->validate(
            [
                'sell' => ['nullable', 'exists:shares,id'],
            ]
        );

        if ($parent_id = $request->input('sell')) {
            return view('shares.sell')
                ->with(
                    [
                        'parent' => Share::findOrFail($parent_id),
                    ]
                );
        }

        return view('shares.create')
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
                'parent_id' => ['nullable', 'exists:shares,id'],
                'stock_id' => ['required', 'exists:stocks,id'],
                'transacted_at' => ['required', 'date_format:Y-m-d'],
                'amount' => ['required', 'integer'],
                'price' => ['required', 'numeric'],
                'exchange_rate' => ['required', 'numeric'],
            ]
        );

        $share = Share::create(
            $request->only('parent_id', 'stock_id', 'transacted_at', 'amount', 'price', 'exchange_rate')
        );

        $message = $request->input('parent_id')
            ? 'The sell ":name (:date)" has been added.'
            : 'The share ":name (:date)" has been added.';

        return redirect()
            ->route('shares.index')
            ->with(
                [
                    'success' => __(
                        $message,
                        [
                            'name' => $share->stock->name,
                            'date' => $share->transacted_at->formatLocalized('%d %B %Y'),
                        ]
                    ),
                ]
            );
    }

    public function show(Request $request, Share $share): Renderable
    {
        return view('shares.show')
            ->with(
                [
                    'share' => $share,
                    'confirmDeletion' => $request->input('delete') == 'confirm',
                ]
            );
    }

    public function edit(Share $share): Renderable
    {
        return view('shares.edit')
            ->with(
                [
                    'share' => $share,
                ]
            );
    }

    public function update(Request $request, Share $share): RedirectResponse
    {
        $request->validate(
            [
                'transacted_at' => ['required', 'date_format:Y-m-d'],
                'amount' => ['required', 'integer'],
                'price' => ['required', 'numeric'],
                'exchange_rate' => ['required', 'numeric'],
            ]
        );

        $fields = ['transacted_at', 'amount', 'price', 'active'];

        if (!$share->parent_id) {
            $request->validate(
                [
                    'active' => ['required', 'boolean'],
                ]
            );

            $fields[] = 'active';
        }

        $share->update($request->only($fields));

        return redirect()
            ->route('shares.show', ['shares' => $share])
            ->with(
                [
                    'success' => __('Your changes have been saved.'),
                ]
            );
    }

    public function destroy(Share $share): RedirectResponse
    {
        try {
            $share->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('shares.show', ['shares' => $share])
                ->with(
                    'warning',
                    __('This item could not be deleted.')
                );
        }

        return redirect()
            ->route('shares.index')
            ->with(
                [
                    'info' => __(
                        'You have succesfully deleted ":name (:date)".',
                        [
                            'name' => $share->stock->name,
                            'date' => $share->transacted_at->formatLocalized('%d %B %Y'),
                        ]
                    ),
                ]
            );
    }
}

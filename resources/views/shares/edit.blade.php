@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>
                @lang('Edit share ":name (:date)"', [ 'name' => $share->stock->name, 'date' => $share->transacted_at->formatLocalized('%d %B %Y') ])
                @if($share->parent)
                    <span class="badge badge-info">@lang('Sell')</span>
                @endif
            </h1>

            <form action="{{ route('shares.update', [ 'shares' => $share ]) }}" method="post">
                @csrf
                @method('PUT')

                @component('form.input')
                    @slot('label', 'Stock')
                    @slot('field', 'stock')
                    @slot('value', $share->stock->name)

                    disabled
                @endcomponent

                @component('form.input')
                    @slot('label', 'Transaction date')
                    @slot('field', 'transacted_at')
                    @slot('value', $share->transacted_at->formatLocalized('%Y-%m-%d'))

                    autofocus
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Amount')
                    @slot('field', 'amount')
                    @slot('value', $share->amount)
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Price')
                    @slot('field', 'price')
                    @slot('value', $share->price)

                    step="0.0001"
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Exchange rate')
                    @slot('field', 'exchange_rate')
                    @slot('value', $share->exchange_rate)

                    step="0.0001"
                @endcomponent

                @if(!$share->parent_id)
                    @component('form.select.yesno')
                        @slot('label', 'Active')
                        @slot('field', 'active')
                        @slot('value', $share->active)
                    @endcomponent
                @endif

                @component('form.submit')
                    @slot('return_route', route('shares.show', [ 'share' => $share ]))
                @endcomponent
            </form>

        </div>
    </div>
</div>
@endsection

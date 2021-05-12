@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Edit stock ":name (:ticker)"', [ 'name' => $stock->name, 'ticker' => $stock->ticker ])</h1>

            <form action="{{ route('stocks.update', [ 'stock' => $stock ]) }}" method="post">
                @csrf
                @method('PUT')

                @component('form.input')
                    @slot('label', 'Ticker')
                    @slot('field', 'ticker')
                    @slot('value', $stock->ticker)

                    autofocus
                @endcomponent

                @component('form.input')
                    @slot('label', 'Name')
                    @slot('field', 'name')
                    @slot('value', $stock->name)
                @endcomponent

                @component('form.select')
                    @slot('label', 'Currency')
                    @slot('field', 'currency_id')
                    @slot('value', $stock->currency->id)
                    @slot('options', $currencies)
                    @slot('option_label', 'code')
                @endcomponent

                @component('form.select')
                    @slot('label', 'Stock exchange')
                    @slot('field', 'exchange_id')
                    @slot('value', $stock->exchange->id)
                    @slot('options', $exchanges)
                @endcomponent

                @component('form.select.yesno')
                    @slot('label', 'Active')
                    @slot('field', 'active')
                    @slot('value', $stock->active)
                @endcomponent

                @component('form.submit')
                    @slot('return_route', route('stocks.show', [ 'stock' => $stock ]))
                @endcomponent
            </form>

        </div>
    </div>
</div>
@endsection

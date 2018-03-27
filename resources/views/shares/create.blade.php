@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Add new share')</h1>

            <form action="{{ route('shares.store') }}" method="post">
                @csrf

                @component('form.select')
                    @slot('label', 'Stock')
                    @slot('field', 'stock_id')
                    @slot('options', $stocks)

                    autofocus
                @endcomponent

                @component('form.input')
                    @slot('label', 'Transaction date')
                    @slot('field', 'transacted_at')
                    @slot('placeholder', 'YYYY-MM-DD')
                    @slot('value', date('Y-m-d'))
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Amount')
                    @slot('field', 'amount')
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Price')
                    @slot('field', 'price')

                    step="0.0001"
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Exchange rate')
                    @slot('field', 'exchange_rate')
                    @slot('value', 1)

                    step="0.0001"
                @endcomponent

                @component('form.submit')
                    @slot('return_route', route('shares.index'))
                @endcomponent
            </form>

        </div>
    </div>
</div>
@endsection

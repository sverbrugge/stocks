@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Add new stock')</h1>

            <form action="{{ route('stocks.store') }}" method="post">
                @csrf

                @component('form.input')
                    @slot('label', 'Ticker')
                    @slot('field', 'ticker')

                    autofocus
                @endcomponent

                @component('form.input')
                    @slot('label', 'Name')
                    @slot('field', 'name')
                @endcomponent

                @component('form.select')
                    @slot('label', 'Currency')
                    @slot('field', 'currency_id')
                    @slot('options', $currencies)
                    @slot('option_label', 'code')
                @endcomponent

                @component('form.select')
                    @slot('label', 'Stock exchange')
                    @slot('field', 'exchange_id')
                    @slot('options', $exchanges)
                @endcomponent

                @component('form.submit')
                    @slot('return_route', route('stocks.index'))
                @endcomponent
            </form>

        </div>
    </div>
</div>
@endsection

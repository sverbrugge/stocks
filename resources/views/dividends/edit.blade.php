@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>
                @lang('Edit dividend ":name (:date)"', [ 'name' => $dividend->stock->name, 'date' => $dividend->transacted_at->formatLocalized('%d %B %Y') ])
                @if($dividend->parent)
                    <span class="badge badge-info">@lang('Sell')</span>
                @endif
            </h1>

            <form action="{{ route('dividends.update', [ 'dividends' => $dividend ]) }}" method="post">
                @csrf
                @method('PUT')

                @component('form.input')
                    @slot('label', 'Stock')
                    @slot('field', 'stock')
                    @slot('value', $dividend->stock->name)

                    disabled
                @endcomponent

                @component('form.input')
                    @slot('label', 'Transaction date')
                    @slot('field', 'transacted_at')
                    @slot('value', $dividend->transacted_at->formatLocalized('%Y-%m-%d'))

                    autofocus
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Price')
                    @slot('field', 'price')
                    @slot('value', $dividend->price)

                    step="0.0001"
                @endcomponent

                @component('form.submit')
                    @slot('return_route', route('dividends.show', [ 'dividends' => $dividend ]))
                @endcomponent
            </form>

        </div>
    </div>
</div>
@endsection

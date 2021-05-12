@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Edit currency ":code"', [ 'code' => $currency->code ])</h1>

            <form action="{{ route('currencies.update', [ 'currency' => $currency ]) }}" method="post">
                @csrf
                @method('PUT')

                @component('form.input')
                    @slot('label', 'Code')
                    @slot('field', 'code')
                    @slot('value', $currency->code)

                    maxlength="3"
                @endcomponent

                @component('form.input')
                    @slot('label', 'Symbol')
                    @slot('field', 'symbol')
                    @slot('value', $currency->symbol)

                    maxlength="1"
                @endcomponent

                @component('form.checkbox')
                    @slot('label', 'Default currency')
                    @slot('field', 'default')
                    @slot('checked', $currency->default)
                @endcomponent

                @component('form.submit')
                    @slot('return_route', route('currencies.show', [ 'currency' => $currency ]))
                @endcomponent
            </form>

        </div>
    </div>
</div>
@endsection

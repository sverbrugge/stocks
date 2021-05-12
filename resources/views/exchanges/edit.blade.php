@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Edit exchange ":name"', [ 'name' => $exchange->name ])</h1>

            <form action="{{ route('exchanges.update', [ 'exchange' => $exchange ]) }}" method="post">
                @csrf
                @method('PUT')

                @component('form.input')
                    @slot('label', 'Name')
                    @slot('field', 'name')
                    @slot('value', $exchange->name)

                    autofocus
                @endcomponent

                @component('form.input')
                    @slot('label', 'Time zone')
                    @slot('field', 'timezone')
                    @slot('value', $exchange->timezone)
                @endcomponent

                @component('form.input')
                    @slot('label', 'Trading from')
                    @slot('field', 'trading_from')
                    @slot('value', $exchange->trading_from)

                    min="0" max="23"
                @endcomponent

                @component('form.input')
                    @slot('label', 'Trading to')
                    @slot('field', 'trading_to')
                    @slot('value', $exchange->trading_to)

                    min="0" max="23"
                @endcomponent

                @component('form.submit')
                    @slot('return_route', route('exchanges.show', [ 'exchange' => $exchange ]))
                @endcomponent
            </form>

        </div>
    </div>
</div>
@endsection

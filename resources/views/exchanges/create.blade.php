@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Add new exchange')</h1>

            <form action="{{ route('exchanges.store') }}" method="post">
                @csrf

                @component('form.input')
                    @slot('label', 'Name')
                    @slot('field', 'name')

                    autofocus
                @endcomponent

                @component('form.input')
                    @slot('label', 'Time zone')
                    @slot('field', 'timezone')
                @endcomponent

                @component('form.input')
                    @slot('label', 'Trading from')
                    @slot('field', 'trading_from')
                    @slot('value', '09:00')
                @endcomponent

                @component('form.input')
                    @slot('label', 'Trading to')
                    @slot('field', 'trading_to')
                    @slot('value', '09:00')
                @endcomponent

                @component('form.submit')
                    @slot('return_route', route('exchanges.index'))
                @endcomponent
            </form>

        </div>
    </div>
</div>
@endsection

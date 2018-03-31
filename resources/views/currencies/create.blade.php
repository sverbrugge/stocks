@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Add new currency')</h1>

            <form action="{{ route('currencies.store') }}" method="post">
                @csrf

                @component('form.input')
                    @slot('label', 'Code')
                    @slot('field', 'code')

                    autofocus maxlength="3"
                @endcomponent

                @component('form.input')
                    @slot('label', 'Symbol')
                    @slot('field', 'symbol')

                    maxlength="1"
                @endcomponent

                @component('form.checkbox')
                    @slot('label', 'Default currency')
                    @slot('field', 'default')
                @endcomponent

                @component('form.submit')
                    @slot('return_route', route('currencies.index'))
                @endcomponent
            </form>

        </div>
    </div>
</div>
@endsection

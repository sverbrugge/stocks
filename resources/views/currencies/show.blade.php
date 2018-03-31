@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Currency ":code"', [ 'code' => $currency->code ])</h1>

            @include('form.messages')

            <form>
                @component('form.input')
                    @slot('label', 'Code')
                    @slot('field', 'code')
                    @slot('value', $currency->code)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('label', 'Symbol')
                    @slot('field', 'symbol')
                    @slot('value', $currency->symbol)

                    readonly
                @endcomponent

                @component('form.checkbox')
                    @slot('label', 'Default currency')
                    @slot('field', 'default')
                    @slot('checked', $currency->default)

                    disabled
                @endcomponent
            </form>

        </div>
    </div>
    <div class="row">
        @if($confirmDeletion)
            <div class="col-md-12 text-center">
                    <form action="{{ route('currencies.destroy', [ 'currencies' => $currency ]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <p class="alert alert-warning">
                            @lang('Do you really want to delete?')
                        </p>

                        <button type="submit" class="btn btn-danger float-left">
                            @lang('Delete')
                        </button>

                        <a role="button" class="btn btn-secondary float-right" href="{{ route('currencies.show', [ 'currencies' => $currency ]) }}">
                            @lang('Cancel')
                        </a>
                    </form>
            </div>
        @else
            <div class="col-md-6">
                <a role="button" class="btn btn-primary" href="{{ route('currencies.edit', [ 'currencies' => $currency ])}}">
                    @lang('Edit')
                </a>
                <a role="button" class="btn btn-secondary" href="{{ route('currencies.index') }}">
                    @lang('Back to index')
                </a>
            </div>
            <div class="col-md-6 text-right">
                <a role="button" class="btn btn-danger" href="{{ route('currencies.show', [ 'currencies' => $currency, 'delete' => 'confirm' ])}}">
                    @lang('Delete')
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

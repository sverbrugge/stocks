@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Exchange ":name"', [ 'name' => $exchange->name ])</h1>

            @include('form.messages')

            <form>
                @component('form.input')
                    @slot('label', 'Name')
                    @slot('field', 'name')
                    @slot('value', $exchange->name)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('label', 'Time zone')
                    @slot('field', 'timezone')
                    @slot('value', $exchange->timezone)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('label', 'Trading from')
                    @slot('field', 'trading_from')
                    @slot('value', $exchange->trading_from)

                    min="0" max="23" readonly
                @endcomponent

                @component('form.input')
                    @slot('label', 'Trading to')
                    @slot('field', 'trading_to')
                    @slot('value', $exchange->trading_to)

                    min="0" max="23" readonly
                @endcomponent
            </form>

        </div>
    </div>
    <div class="row">
        @if($confirmDeletion)
            <div class="col-md-12 text-center">
                    <form action="{{ route('exchanges.destroy', [ 'exchange' => $exchange ]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <p class="alert alert-warning">
                            @lang('Do you really want to delete?')
                        </p>

                        <button type="submit" class="btn btn-danger float-left">
                            @lang('Delete')
                        </button>

                        <a role="button" class="btn btn-secondary float-right" href="{{ route('exchanges.show', [ 'exchange' => $exchange ]) }}">
                            @lang('Cancel')
                        </a>
                    </form>
            </div>
        @else
            <div class="col-md-6">
                <a role="button" class="btn btn-primary" href="{{ route('exchanges.edit', [ 'exchange' => $exchange ])}}">
                    @lang('Edit')
                </a>
                <a role="button" class="btn btn-secondary" href="{{ route('exchanges.index') }}">
                    @lang('Back to index')
                </a>
            </div>
            <div class="col-md-6 text-right">
                <a role="button" class="btn btn-danger" href="{{ route('exchanges.show', [ 'exchange' => $exchange, 'delete' => 'confirm' ])}}">
                    @lang('Delete')
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

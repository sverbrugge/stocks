@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>@lang('Stock ":name (:ticker)"', [ 'name' => $stock->name, 'ticker' => $stock->ticker ])</h1>

            @include('form.messages')

            <form>
                @component('form.input')
                    @slot('label', 'Ticker')
                    @slot('field', 'ticker')
                    @slot('value', $stock->ticker)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('label', 'Name')
                    @slot('field', 'name')
                    @slot('value', $stock->name)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('label', 'Currency')
                    @slot('field', 'currency')
                    @slot('value', $stock->currency->code)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('label', 'Stock exchange')
                    @slot('field', 'exchange')
                    @slot('value', $stock->exchange->name)

                    readonly
                @endcomponent
            </form>

        </div>
    </div>
    <div class="row">
        @if($confirmDeletion)
            <div class="col-md-12 text-center">
                    <form action="{{ route('stocks.destroy', [ 'stocks' => $stock ]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <p class="alert alert-warning">
                            @lang('Do you really want to delete?')
                        </p>

                        <button type="submit" class="btn btn-danger float-left">
                            @lang('Delete')
                        </button>

                        <a role="button" class="btn btn-secondary float-right" href="{{ route('stocks.show', [ 'stocks' => $stock ]) }}">
                            @lang('Cancel')
                        </a>
                    </form>
            </div>
        @else
            <div class="col-md-6">
                <a role="button" class="btn btn-primary" href="{{ route('stocks.edit', [ 'stocks' => $stock ])}}">
                    @lang('Edit')
                </a>
                <a role="button" class="btn btn-secondary" href="{{ route('stocks.index') }}">
                    @lang('Back to index')
                </a>
            </div>
            <div class="col-md-6 text-right">
                <a role="button" class="btn btn-danger" href="{{ route('stocks.show', [ 'stocks' => $stock, 'delete' => 'confirm' ])}}">
                    @lang('Delete')
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>
                @lang('Dividend ":name (:date)"', [ 'name' => $dividend->stock->name, 'date' => $dividend->transacted_at->formatLocalized('%d %B %Y') ])
                @if($dividend->parent)
                    <span class="badge badge-info">@lang('Sell')</span>
                @endif
            </h1>

            @include('form.messages')

            <form>
                @component('form.input')
                    @slot('label', 'Stock')
                    @slot('field', 'stock')
                    @slot('value', $dividend->stock->name)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('label', 'Transaction date')
                    @slot('field', 'transacted_at')
                    @slot('value', $dividend->transacted_at->formatLocalized('%Y-%m-%d'))

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Price')
                    @slot('field', 'price')
                    @slot('value', $dividend->price)

                    readonly
                @endcomponent
            </form>

        </div>
    </div>
    <div class="row">
        @if($confirmDeletion)
            <div class="col-md-12 text-center">
                    <form action="{{ route('dividends.destroy', [ 'dividend' => $dividend ]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <p class="alert alert-warning">
                            @lang('Do you really want to delete?')
                        </p>

                        <button type="submit" class="btn btn-danger float-left">
                            @lang('Delete')
                        </button>

                        <a role="button" class="btn btn-secondary float-right" href="{{ route('dividends.show', [ 'dividend' => $dividend ]) }}">
                            @lang('Cancel')
                        </a>
                    </form>
            </div>
        @else
            <div class="col-md-6">
                <a role="button" class="btn btn-primary" href="{{ route('dividends.edit', [ 'dividend' => $dividend ])}}">
                    @lang('Edit')
                </a>
                <a role="button" class="btn btn-secondary" href="{{ route('dividends.index') }}">
                    @lang('Back to index')
                </a>
            </div>
            <div class="col-md-6 text-right">
                <a role="button" class="btn btn-danger" href="{{ route('dividends.show', [ 'dividend' => $dividend, 'delete' => 'confirm' ])}}">
                    @lang('Delete')
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

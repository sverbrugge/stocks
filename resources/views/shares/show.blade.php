@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>
                @lang('Share ":name (:date)"', [ 'name' => $share->stock->name, 'date' => $share->transacted_at->formatLocalized('%d %B %Y') ])
                @if($share->parent)
                    <span class="badge badge-info">@lang('Sell')</span>
                @endif
            </h1>

            @include('form.messages')

            <form>
                @component('form.input')
                    @slot('label', 'Stock')
                    @slot('field', 'stock')
                    @slot('value', $share->stock->name)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('label', 'Transaction date')
                    @slot('field', 'transacted_at')
                    @slot('value', $share->transacted_at->formatLocalized('%Y-%m-%d'))

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Amount')
                    @slot('field', 'amount')
                    @slot('value', $share->amount)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Price')
                    @slot('field', 'price')
                    @slot('value', $share->price)

                    readonly
                @endcomponent

                @component('form.input')
                    @slot('type', 'number')
                    @slot('label', 'Exchange rate')
                    @slot('field', 'exchange_rate')
                    @slot('value', $share->exchange_rate)

                    readonly
                @endcomponent

                @if(!$share->parent_id)
                    @component('form.input')
                        @slot('label', 'Active')
                        @slot('field', 'active')
                        @slot('value', __($share->active ? 'Yes' : 'No'))

                        readonly
                    @endcomponent
                @endif
            </form>

        </div>
    </div>
    <div class="row">
        @if($confirmDeletion)
            <div class="col-md-12 text-center">
                    <form action="{{ route('shares.destroy', [ 'share' => $share ]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <p class="alert alert-warning">
                            @lang('Do you really want to delete?')
                        </p>

                        <button type="submit" class="btn btn-danger float-left">
                            @lang('Delete')
                        </button>

                        <a role="button" class="btn btn-secondary float-right" href="{{ route('shares.show', [ 'share' => $share ]) }}">
                            @lang('Cancel')
                        </a>
                    </form>
            </div>
        @else
            <div class="col-md-4">
                <a role="button" class="btn btn-primary" href="{{ route('shares.edit', [ 'share' => $share ])}}">
                    @lang('Edit')
                </a>
                <a role="button" class="btn btn-secondary" href="{{ route('shares.index', ['inactive' => (($share->parent && !$share->parent->active) || !$share->active) ?: null]) }}">
                    @lang('Back to index')
                </a>
            </div>
            <div class="col-md-4 text-center">
                @unless($share->parent || $share->allSharesSold)
                    <a role="button" class="btn btn-info" href="{{ route('shares.create', [ 'sell' => $share ])}}">
                        @lang('Sell')
                    </a>
                @endunless
            </div>
            <div class="col-md-4 text-right">
                <a role="button" class="btn btn-danger" href="{{ route('shares.show', [ 'share' => $share, 'delete' => 'confirm' ])}}">
                    @lang('Delete')
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

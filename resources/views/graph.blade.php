@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ $stock->name }} <small class="text-muted">{{ $stock->ticker }}</small></h1>

            @foreach ($stock->activeShares->unique('price')->sortBy('price') as $share)
                <h2>
                    {{ $share->price }}
                    <small class="text-{{ $share->colorClass }}" title="{{ $stock->currentQuote->quoted_at }} ({{ $stock->currentQuote->quoted_at->diffForHumans() }})">
                        {{ $share->gain }}
                        ({{ $share->percentGain }})
                    </small>
                </h2>
            @endforeach

            <stock-graph id="{{ $stock->id }}" name="{{ $stock->ticker }}" lines="{{ $stock->activeShares->unique('price')->pluck('price') }}"></stock-graph>
        </div>
    </div>
</div>
@endsection

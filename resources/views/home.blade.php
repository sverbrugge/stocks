@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($totals as $currencyCode => $gainLoss)
            <div class="col-md-6 text-center">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="3">{{ $currencyCode }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-right alert-success">{{ sprintf('%0.4f', $gainLoss['gain']) }}</td>
                            <td class="text-right alert-danger">{{ sprintf('%0.4f', $gainLoss['loss']) }}</td>
                            <td class="text-right alert-{{ $gainLoss['gain'] + $gainLoss['loss'] >= 0 ? 'success' : 'danger' }}">{{ sprintf('%0.4f', $gainLoss['gain'] + $gainLoss['loss']) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>
<p>&nbsp;</p>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            @lang('Share')
                        </th>
                        <th>
                            @lang('Currency')
                        </th>
                        <th>
                            @lang('Date')
                        </th>
                        <th>
                            @lang('Duration')
                        </th>
                        <th>
                            @lang('Amount')
                        </th>
                        <th>
                            @lang('Price')
                        </th>
                        <th>
                            @lang('Total')
                        </th>
                        <th>
                            @lang('Current')
                        </th>
                        <th>
                            @lang('Gain')
                        </th>
                    </tr>
                </thead>
                    @forelse($shares as $exchangeName => $exchangeShares)
                        <thead class="section">
                            <tr>
                                <th>{{ $exchangeName }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exchangeShares as $share)
                                <tr>
                                    <td>
                                        <a href="{{ route('graph', [ 'stock' => $share->stock->id ]) }}">{{ $share->stock->name }}</a>
                                        @if($share->allSharesSold)
                                            <span class="badge badge-info">@lang('All shares sold')</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $share->stock->currency->code }}
                                    </td>
                                    <td>
                                        {{ $share->transacted_at->toDateString() }}
                                    </td>
                                    <td>
                                        {{ $share->transacted_at->diffForHumans() }}
                                    </td>
                                    <td class="text-right">
                                        {{ $share->amount }}
                                    </td>
                                    <td class="text-right">
                                        {{ $share->price }}
                                    </td>
                                    <td class="text-right">
                                        {{ $share->totalPrice }}
                                    </td>
                                    @if($currentQuote = $share->stock->currentQuote)
                                        <td class="text-right table-{{ $share->colorClass }}" title="{{ $currentQuote->quoted_at }} ({{ $currentQuote->quoted_at->diffForHumans() }})">
                                            {{ $currentQuote->price }}
                                        </td>
                                    @else
                                        <td class="text-right table-{{ $share->colorClass }}">

                                        </td>
                                    @endif
                                    <td class="text-right table-{{ $share->colorClass }}">
                                        {{ $share->percentGain }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @empty
                        <tbody>
                            <tr>
                                <td colspan="9">
                                    @lang('No shares found')
                                </td>
                            </tr>
                        </tbody>
                    @endforelse
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
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
                <tbody>
                    @forelse($shares as $share)
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
                            <?php $currentQuote = $share->stock->currentQuote; ?>
                            @if($currentQuote)
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
                    @empty
                        <tr>
                            <td colspan="9">
                                @lang('No shares found')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

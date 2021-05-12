@extends('layouts.app')

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>@lang('Gains')</h1>

                    <div class="float-right">
                        {{ $shares->links() }}
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    @lang('Stock')
                                </th>
                                <th>
                                    @lang('Date')
                                </th>
                                <th>
                                    @lang('Currency')
                                </th>
                                <th>
                                    @lang('Bought')
                                </th>
                                <th>
                                    @lang('Sold')
                                </th>
                                <th>
                                    @lang('Gain')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($shares as $share)
                                <tr class="">
                                    <td>
                                        <a href="{{ route('stocks.show', [ 'stock' => $share->stock ])}}">
                                            {{ $share->stock->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $share->transacted_at->toDateString() }}
                                    </td>
                                    <td>
                                        {{ $share->stock->currency->code }}
                                    </td>
                                    <td class="text-right">
                                        {{ $share->parent->totalPrice }}
                                    </td>
                                    <td class="text-right">
                                        {{ $share->totalPrice }}
                                    </td>
                                    <td class="text-right table-{{ $share->gainColorClass }}">
                                        {{ $share->soldGain }}
                                    </td>
                                    <td class="text-right table-{{ $share->gainColorClass }}">
                                        {{ $share->soldGainPercent }}%
                                    </td>
                                </tr>
                            @empty
                                @lang('No sold shares could be found')
                            @endforelse
                        </tbody>
                    </table>

                    <div class="float-right">
                        {{ $shares->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection

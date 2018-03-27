@extends('layouts.app')

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>@lang('Shares')</h1>

                    @include('form.messages')

                    <div class="float-left">
                        <a class="btn btn-primary" href="{{ route('shares.create') }}">@lang('Add new share')</a>
                    </div>

                    <div class="float-right">
                        {{ $shares->links() }}
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    @lang('Stock')
                                </th>
                                <th class="text-right">
                                    @lang('Transaction date')
                                </th>
                                <th class="text-right">
                                    @lang('Amount')
                                </th>
                                <th class="text-right">
                                    @lang('Price')
                                </th>
                                <th class="text-right">
                                    @lang('Exchange rate')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shares as $share)
                                <tr>
                                    <td>
                                        <a href="{{ route('shares.show', [ 'shares' => $share ])}}">
                                            {{ $share->stock->name }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                            {{ $share->transacted_at->formatLocalized('%d %B %Y') }}
                                    </td>
                                    <td class="text-right">
                                            {{ $share->amount }}
                                    </td>
                                    <td class="text-right">
                                            {{ $share->price }}
                                    </td>
                                    <td class="text-right">
                                            {{ $share->exchange_rate }}
                                    </td>
                                </tr>

                                @foreach($share->children as $child)
                                    <tr>
                                        <td class="text-right">
                                            <a href="{{ route('shares.show', [ 'shares' => $child ])}}">
                                                <span class="badge badge-info">@lang('Sell')</span>
                                            </a>
                                        </td>
                                        <td class="text-right">
                                                {{ $child->transacted_at->formatLocalized('%d %B %Y') }}
                                        </td>
                                        <td class="text-right">
                                                {{ $child->amount }}
                                        </td>
                                        <td class="text-right">
                                                {{ $child->price }}
                                        </td>
                                        <td class="text-right">
                                                {{ $child->exchange_rate }}
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                @lang('No shares could be found')
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

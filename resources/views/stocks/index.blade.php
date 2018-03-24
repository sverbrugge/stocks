@extends('layouts.app')

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>@lang('Stocks')</h1>

                    @include('form.messages')

                    <div class="float-left">
                        <a class="btn btn-primary" href="{{ route('stocks.create') }}">@lang('Add new stock')</a>
                    </div>

                    <div class="float-right">
                        {{ $stocks->links() }}
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    @lang('Ticker')
                                </th>
                                <th>
                                    @lang('Name')
                                </th>
                                <th>
                                    @lang('Exchange')
                                </th>
                                <th>
                                    @lang('Currency')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stocks as $stock)
                                <tr>
                                    <td>
                                            {{ $stock->ticker }}
                                    </td>
                                    <td>
                                        <a href="{{ route('stocks.show', [ 'stocks' => $stock ])}}">
                                            {{ $stock->name }}
                                        </a>
                                    </td>
                                    <td>
                                            {{ $stock->exchange->name }}
                                    </td>
                                    <td>
                                            {{ $stock->currency->code }}
                                    </td>
                                </tr>
                            @empty
                                @lang('No stocks could be found')
                            @endforelse
                        </tbody>
                    </table>

                    <div class="float-right">
                        {{ $stocks->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection

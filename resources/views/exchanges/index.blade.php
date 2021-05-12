@extends('layouts.app')

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>@lang('Exchanges')</h1>

                    @include('form.messages')

                    <div class="float-left">
                        <a class="btn btn-primary" href="{{ route('exchanges.create') }}">@lang('Add new exchange')</a>
                    </div>

                    <div class="float-right">
                        {{ $exchanges->links() }}
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    @lang('Name')
                                </th>
                                <th>
                                    @lang('Time zone')
                                </th>
                                <th>
                                    @lang('Trading from')
                                </th>
                                <th>
                                    @lang('Trading to')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($exchanges as $exchange)
                                <tr>
                                    <td>
                                        <a href="{{ route('exchanges.show', [ 'exchange' => $exchange ])}}">
                                            {{ $exchange->name }}
                                        </a>
                                    </td>
                                    <td>
                                            {{ $exchange->timezone }}
                                    </td>
                                    <td>
                                            {{ $exchange->trading_from }}
                                    </td>
                                    <td>
                                            {{ $exchange->trading_to }}
                                    </td>
                                </tr>
                            @empty
                                @lang('No exchanges could be found')
                            @endforelse
                        </tbody>
                    </table>

                    <div class="float-right">
                        {{ $exchanges->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection

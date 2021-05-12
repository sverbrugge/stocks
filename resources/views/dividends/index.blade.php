@extends('layouts.app')

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>@lang('Dividends')</h1>

                    @include('form.messages')

                    <div class="float-left">
                        <a class="btn btn-primary" href="{{ route('dividends.create') }}">@lang('Add new dividend')</a>
                    </div>

                    <div class="float-right">
                        {{ $dividends->links() }}
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
                                    @lang('Price')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dividends as $dividend)
                                <tr>
                                    <td>
                                        <a href="{{ route('dividends.show', [ 'dividend' => $dividend ])}}">
                                            {{ $dividend->stock->name }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                            {{ $dividend->transacted_at->formatLocalized('%d %B %Y') }}
                                    </td>
                                    <td class="text-right">
                                            {{ $dividend->price }}
                                    </td>
                                </tr>
                            @empty
                                @lang('No dividends could be found')
                            @endforelse
                        </tbody>
                    </table>

                    <div class="float-right">
                        {{ $dividends->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection

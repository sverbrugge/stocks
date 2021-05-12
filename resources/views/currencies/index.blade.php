@extends('layouts.app')

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>@lang('Currencies')</h1>

                    @include('form.messages')

                    <div class="float-left">
                        <a class="btn btn-primary" href="{{ route('currencies.create') }}">@lang('Add new currency')</a>
                    </div>

                    <div class="float-right">
                        {{ $currencies->links() }}
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                    @lang('Code')
                                </th>
                                <th>
                                    @lang('Symbol')
                                </th>
                                <th>
                                    @lang('Default currency')
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($currencies as $currency)
                                <tr>
                                    <td>
                                        <a href="{{ route('currencies.show', [ 'currency' => $currency ])}}">
                                            {{ $currency->code }}
                                        </a>
                                    </td>
                                    <td>
                                            {{ $currency->symbol }}
                                    </td>
                                    <td>
                                            @lang($currency->default ? 'Yes' : 'No')
                                    </td>
                                </tr>
                            @empty
                                @lang('No currencies could be found')
                            @endforelse
                        </tbody>
                    </table>

                    <div class="float-right">
                        {{ $currencies->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection

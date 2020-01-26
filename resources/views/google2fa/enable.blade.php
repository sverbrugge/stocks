@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>@lang('Enable two-factor authentication')</h1>

                @include('form.messages')

                <form action="{{ route('google2fa.check') }}" method="post">
                    @csrf

                    <p style="text-align: center">
                        {!! $qrCode !!}
                    </p>

                    @component('form.input')
                        @slot('label', 'Key')
                        @slot('field', 'key')

                        autocomplete="off"
                    @endcomponent

                    @component('form.submit')
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
@endsection

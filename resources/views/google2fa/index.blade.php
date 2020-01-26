@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>@lang('Two-factor authentication')</h1>

                @include('form.messages')

                <form action="{{ route('google2fa.authenticate') }}" method="post">
                    @csrf

                    @component('form.input')
                        @slot('label', 'Key')
                        @slot('field', config('google2fa.otp_input'))

                        autocomplete="off"
                    @endcomponent

                    @component('form.submit')
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
@endsection

@component('mail::message')
# Stocks report

@component('mail::table')

| @lang('Ticker') | @lang('Quote') | @lang('Difference') | @lang('Percentage') |
|-----------------|---------------:|--------------------:|--------------------:|
@foreach ($report as $row)
| {{ $row['ticker'] }} | {{ sprintf('%.04f', $row['last_quote']) }} | {{ sprintf('%.04f', $row['difference']) }} | {{ sprintf('%.02f', $row['percentage']) }}% |
@endforeach

@endcomponent

@endcomponent

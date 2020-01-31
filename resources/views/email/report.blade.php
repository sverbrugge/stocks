<!DOCTYPE html>
<html>
<head>
    <title>Stocks Report</title>

    <style>
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: sans-serif;
            font-size: 1em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ffffff;
            padding: 5pt;
        }

        tbody tr:nth-child(2n+1) td {
            background-color: #343a40;
        }

        tbody tr.gain td+td+td+td {
            background-color: #1c7430;
        }

        tbody tr.loss td+td+td+td {
            background-color: #721c24;
        }

        .right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Stocks report</h1>

    <table>
        <thead>
            <tr>
                <th>@lang('Ticker')</th>
                <th>@lang('From')</th>
                <th>@lang('To')</th>
                <th>@lang('Quote')</th>
                <th>@lang('Difference')</th>
                <th>@lang('Percentage')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $row)
                <tr class="{{ $row['difference'] < 0 ? 'loss' : ($row['difference'] > 0 ? 'gain' : 'level') }}">
                    <td title="{{ $row['stock']->name }}">{{ $row['stock']->ticker }}</td>
                    <td>{{ $row['first_quote_date'] }}</td>
                    <td>{{ $row['last_quote_date'] }}</td>
                    <td class="right">{{ sprintf('%.04f', $row['last_quote']) }}</td>
                    <td class="right">{{ sprintf('%.04f', $row['difference']) }}</td>
                    <td class="right">{{ sprintf('%.02f', $row['percentage']) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
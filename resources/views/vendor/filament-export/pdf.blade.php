<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $fileName }}</title>
    <style type="text/css" media="all">
        * {
            /* font-family: DejaVu Sans, sans-serif !important; */
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        }

        html{
            width:100%;
        }

        body {
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border-radius: 10px 10px 10px 10px;
        }

        table td,
        th {
            border-color: #ededed;
            border-style: solid;
            border-width: 1px;
            font-size: 14px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        table th {
            font-weight:bold;
            background-color: #ededed;
        }

        h2 {
            text-align: center;
        }

        .imagen-logo {
            width: 100%;
            text-align: left;
            vertical-align: middle;
            height: 20px;
        }
        .date{
            float: right;
            font-size: 12px;
            color: #535353;
        }

        img {
            max-width: 140px;
            height: auto;
            position: absolute;
            top: -60px;
        }

    </style>
</head>
<body>
    <div class="imagen-logo">
        @if (file_exists(public_path('images/logo-emarket.png')))
            <img src="{{ public_path('images/logo-emarket.png') }}" alt="Logo eMarket">
        @endif
        <p class="date">{{ isset($date) ? $date : '' }}</p>
    </div>


    <h2>{{ isset($title) ? $title : 'Reporte' }}</h2>
    <table>
        <tr>
            @foreach ($columns as $column)
                <th>
                    {{ $column->getLabel() }}
                </th>
            @endforeach
        </tr>
        @foreach ($rows as $row)
            <tr>
                @foreach ($columns as $column)
                    <td>
                        {{ $row[$column->getName()] }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        h2 {
            text-align: center;
        }

        .total {
            font-weight: bold;
            background: #f2f2f2;
        }
    </style>
</head>

<body>

<h2>REPORTE MENSUAL DE VENTAS</h2>

<p><strong>Fecha de emisión:</strong> {{ now() }}</p>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Unidades Vendidas</th>
            <th>Ingresos</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->product_name }}</td>
            <td>{{ $row->total_units }}</td>
            <td>${{ number_format($row->total_income, 2) }}</td>
        </tr>
        @endforeach

        <tr class="total">
            <td>TOTAL</td>
            <td>{{ $totalUnits }}</td>
            <td>${{ number_format($totalIncome, 2) }}</td>
        </tr>
    </tbody>
</table>

</body>
</html>
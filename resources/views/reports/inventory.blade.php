@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Reporte de Inventario</h1>

    <nav id="report-index">
        <ul>
            @foreach($reportIndex as $index => $section)
                <li><a href="#section-{{ $index }}">{{ $section }}</a></li>
            @endforeach
        </ul>
    </nav>

    <section id="section-0">
        <h2>{{ $reportIndex[0] }}</h2>
        <p>Valor total del inventario: ${{ number_format($totalValue, 2) }}</p>
        <p>Total de productos: {{ $inventories->count() }}</p>
        <p>Productos con bajo stock: {{ $lowStockProducts->count() }}</p>
    </section>

    <section id="section-1">
        <h2>{{ $reportIndex[1] }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad actual</th>
                    <th>Stock mínimo</th>
                    <th>Almacén</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $inventory)
                    <tr>
                        <td>{{ $inventory->product->name }}</td>
                        <td>{{ $inventory->quantity }}</td>
                        <td>{{ $inventory->product->min_stock }}</td>
                        <td>{{ $inventory->warehouse->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <section id="section-2">
        <h2>{{ $reportIndex[2] }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Almacén</th>
                    <th>Total de artículos</th>
                    <th>Productos únicos</th>
                    <th>Valor total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($warehouseSummary as $summary)
                    <tr>
                        <td>{{ $summary['name'] }}</td>
                        <td>{{ $summary['total_items'] }}</td>
                        <td>{{ $summary['unique_products'] }}</td>
                        <td>${{ number_format($summary['value'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <section id="section-3">
        <h2>{{ $reportIndex[3] }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Almacén</th>
                    <th>Cantidad</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inventory)
                    <tr>
                        <td>{{ $inventory->product->name }}</td>
                        <td>{{ $inventory->warehouse->name }}</td>
                        <td>{{ $inventory->quantity }}</td>
                        <td>${{ number_format($inventory->quantity * $inventory->product->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
@endsection
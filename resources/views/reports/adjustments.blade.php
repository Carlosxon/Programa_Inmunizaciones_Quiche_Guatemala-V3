@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Reporte de Ajustes</h1>

    <nav id="report-index">
        <ul>
            @foreach($reportIndex as $index => $section)
                <li><a href="#section-{{ $index }}">{{ $section }}</a></li>
            @endforeach
        </ul>
    </nav>

    <section id="section-0">
        <h2>{{ $reportIndex[0] }}</h2>
        <p>Total de ajustes: {{ $adjustments->count() }}</p>
        <p>Cantidad neta ajustada: {{ $adjustments->sum('adjustment_quantity') }}</p>
    </section>

    <section id="section-1">
        <h2>{{ $reportIndex[1] }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Razón</th>
                    <th>Cantidad de ajustes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adjustmentReasons as $reason => $count)
                    <tr>
                        <td>{{ $reason }}</td>
                        <td>{{ $count }}</td>
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
                    <th>Producto</th>
                    <th>Total de ajustes</th>
                    <th>Cantidad neta ajustada</th>
                    <th>Valor total ajustado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adjustmentsByProduct as $adjustment)
                    <tr>
                        <td>{{ $adjustment['name'] }}</td>
                        <td>{{ $adjustment['total_adjustments'] }}</td>
                        <td>{{ $adjustment['net_quantity'] }}</td>
                        <td>${{ number_format($adjustment['value'], 2) }}</td>
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
                    <th>Almacén</th>
                    <th>Total de ajustes</th>
                    <th>Cantidad neta ajustada</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adjustmentsByWarehouse as $adjustment)
                    <tr>
                        <td>{{ $adjustment['name'] }}</td>
                        <td>{{ $adjustment['total_adjustments'] }}</td>
                        <td>{{ $adjustment['net_quantity'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <section id="section-4">
        <h2>{{ $reportIndex[4] }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Almacén</th>
                    <th>Cantidad ajustada</th>
                    <th>Razón</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adjustments as $adjustment)
                    <tr>
                        <td>{{ $adjustment->created_at }}</td>
                        <td>{{ $adjustment->product->name }}</td>
                        <td>{{ $adjustment->warehouse->name }}</td>
                        <td>{{ $adjustment->adjustment_quantity }}</td>
                        <td>{{ $adjustment->reason }}</td>
                   
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
@endsection
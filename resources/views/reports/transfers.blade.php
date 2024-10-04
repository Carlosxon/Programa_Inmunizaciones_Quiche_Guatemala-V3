@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Reporte de Transferencias</h1>

    <nav id="report-index">
        <ul>
            @foreach($reportIndex as $index => $section)
                <li><a href="#section-{{ $index }}">{{ $section }}</a></li>
            @endforeach
        </ul>
    </nav>

    <section id="section-0">
        <h2>{{ $reportIndex[0] }}</h2>
        <p>Total de transferencias: {{ $transfers->count() }}</p>
        <p>Cantidad total transferida: {{ $transfers->sum(function($t) { return $t->transfers->sum('quantity'); }) }}</p>
    </section>

    <section id="section-1">
        <h2>{{ $reportIndex[1] }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad total transferida</th>
                    <th>Valor total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfersByProduct as $transfer)
                    <tr>
                        <td>{{ $transfer['name'] }}</td>
                        <td>{{ $transfer['quantity'] }}</td>
                        <td>${{ number_format($transfer['value'], 2) }}</td>
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
                    <th>Transferencias salientes</th>
                    <th>Cantidad total transferida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfersByWarehouse as $transfer)
                    <tr>
                        <td>{{ $transfer['name'] }}</td>
                        <td>{{ $transfer['outgoing'] }}</td>
                        <td>{{ $transfer['total_quantity'] }}</td>
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
                    <th>Usuario</th>
                    <th>Transferencias realizadas</th>
                    <th>Cantidad total transferida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topUsers as $user)
                    <tr>
                       
                        <td>{{ $user['transfers'] }}</td>
                        <td>{{ $user['total_quantity'] }}</td>
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
                    <th>Desde</th>
                    <th>Hacia</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfers as $transfer)
                    @foreach($transfer->transfers as $detail)
                        <tr>
                            <td>{{ $transfer->transfer_date }}</td>
                            <td>{{ $transfer->fromWarehouse->name }}</td>
                            <td>{{ $transfer->toWarehouse->name }}</td>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </section>
</div>
@endsection
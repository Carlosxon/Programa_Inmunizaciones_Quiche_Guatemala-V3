@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Reporte de Actividad de Usuarios</h1>

    <nav id="report-index">
        <ul>
            @foreach($reportIndex as $index => $section)
                <li><a href="#section-{{ $index }}">{{ $section }}</a></li>
            @endforeach
        </ul>
    </nav>

    <section id="section-0">
        <h2>{{ $reportIndex[0] }}</h2>
        <p>Total de usuarios: {{ $userActivity->count() }}</p>
        <p>Total de acciones: {{ $userActivity->sum('total_actions') }}</p>
    </section>

    <section id="section-1">
        <h2>{{ $reportIndex[1] }}</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Transferencias realizadas</th>
                    <th>Cantidad total transferida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topUsersByTransfers as $user)
                    <tr>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['transfers'] }}</td>
                        <td>{{ $user['total_quantity'] }}</td>
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
                    <th>Usuario</th>
                    <th>Ajustes realizados</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topUsersByAdjustments as $user)
                    <tr>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['adjustments'] }}</td>
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
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Transferencias</th>
                    <th>Ajustes</th>
                    <th>Total de acciones</th>
                    <th>Última actividad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userActivity as $user)
                    <tr>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['role'] }}</td>
                        <td>{{ $user['transfers'] }}</td>
                        <td>{{ $user['adjustments'] }}</td>
                        <td>{{ $user['total_actions'] }}</td>
                        <td>{{ $user['last_activity'] ? $user['last_activity']->format('Y-m-d H:i:s') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
@endsection
@extends('adminlte::page')

@section('title', 'Configuraciones del Sistema')

@section('content_header')
    <h1>Configuraciones del Sistema</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('system-settings.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="key">Clave:</label>
                            <input type="text" name="key" id="key" class="form-control" value="{{ request('key') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="value">Valor:</label>
                            <input type="text" name="value" id="value" class="form-control" value="{{ request('value') }}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('system-settings.index') }}" class="btn btn-secondary">Limpiar filtros</a>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Listado de Configuraciones</h3>
            <div class="card-tools">
                <a href="{{ route('system-settings.create') }}" class="btn btn-primary">Nueva Configuración</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Clave</th>
                        <th>Valor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($settings as $setting)
                        <tr>
                            <td>{{ $setting->id }}</td>
                            <td>{{ $setting->key }}</td>
                            <td>{{ $setting->value }}</td>
                            <td>
                                <a href="{{ route('system-settings.edit', $setting->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('system-settings.destroy', $setting->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($settings->hasPages())
            <div class="card-footer clearfix">
                {{ $settings->links() }}
            </div>
        @endif
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Vista de configuraciones del sistema cargada.');
    </script>
@stop

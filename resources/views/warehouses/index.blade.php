@extends('adminlte::page')

@section('title', 'Warehouses')

@section('content_header')
    <h1>Warehouses</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Almacenes</h3>
                    @if(!auth()->user()->hasRole('encargado_sucursal'))
                        <div class="card-tools">
                            <a href="{{ route('warehouses.create') }}" class="btn btn-primary">Crear Nuevo</a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Formulario de Filtros -->
                    <form method="GET" action="{{ route('warehouses.index') }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}" placeholder="Buscar por nombre">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="location">Ubicación</label>
                                <input type="text" name="location" id="location" class="form-control" value="{{ request('location') }}" placeholder="Buscar por ubicación">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="type">Tipo</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="">Seleccionar tipo</option>
                                    <option value="regular" {{ request('type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="premium" {{ request('type') == 'premium' ? 'selected' : '' }}>Premium</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">Estado</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Seleccionar estado</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 mt-4">
                                <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                            </div>
                        </div>
                    </form>
                    
                    <table class="table table-bordered table-striped mt-4">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Ubicación</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $warehouse->id }}</td>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->location }}</td>
                                    <td>{{ $warehouse->type }}</td>
                                    <td>{{ $warehouse->status }}</td>
                                    <td>
                                        <a href="{{ route('warehouses.show', $warehouse->id) }}" class="btn btn-sm btn-info">Vista</a>
                                        @if(!auth()->user()->hasRole('Encargado de Sucursal'))
                                            <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                            <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta bodega/distrito?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('trasferencias.pendientes', $warehouse->id) }}" class="btn btn-sm btn-success">Ver Inventario</a>
                                        <a href="{{ route('stock-transfers.create') }}?from_warehouse_id={{ $warehouse->id }}" class="btn btn-sm btn-primary">Transferir Stock</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No se encontraron bodegas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Verificación para mostrar paginación solo si es necesaria -->
                    @if ($warehouses->hasPages())
                        <div class="pagination-wrapper">
                            {{ $warehouses->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

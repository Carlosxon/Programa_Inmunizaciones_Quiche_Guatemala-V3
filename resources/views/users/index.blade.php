@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('users.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Nombre:</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ request('email') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="role">Rol:</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">Todos los roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Limpiar filtros</a>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Listado de Usuarios</h3>
            <div class="card-tools">
                <a href="{{ route('users.create') }}" class="btn btn-primary">Crear Nuevo Usuario</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{ $user->roles->pluck('name')->implode(', ') }}
                                <button type="button" class="btn btn-sm btn-info ml-2" data-toggle="modal" data-target="#userInfoModal{{ $user->id }}">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                            <td>
                                <a href="{{ $user->id!=1?route('users.show', $user->id):'javascript:alert(\'ERROR NO TIENES PERMISOS\')' }}" class="btn btn-sm btn-info">Ver</a>
                                <a href="{{ $user->id!=1?route('users.edit', $user->id):'javascript:alert(\'ERROR NO TIENES PERMISOS\')' }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
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
        @if($users->hasPages())
            <div class="card-footer clearfix">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modales de información de usuario -->
    @foreach($users as $user)
        <div class="modal fade" id="userInfoModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="userInfoModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="userInfoModalLabel{{ $user->id }}">Información de {{ $user->name }}</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>ID:</strong> {{ $user->id }}</li>
                                    <li class="list-group-item"><strong>Nombre:</strong> {{ $user->name }}</li>
                                    <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                                    <li class="list-group-item"><strong>Teléfono:</strong> {{ $user->phone }}</li>
                                    <li class="list-group-item"><strong>Dirección:</strong> {{ $user->address }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <strong>Roles:</strong>
                                        <ul>
                                            @foreach($user->roles as $role)
                                                <li>{{ $role->name }}</li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Permisos:</strong>
                                        <ul>
                                            @foreach($user->permissions as $permission)
                                                <li>{{ $permission->name }}</li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Almacenes:</strong>
                                        <ul>
                                            @foreach($user->warehouses as $warehouse)
                                                <li>{{ $warehouse->name }}</li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .modal-content {
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .modal-header {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .list-group-item {
            border: none;
            padding: 0.75rem 1.25rem;
            margin-bottom: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }
        .list-group-item strong {
            color: #495057;
        }
        .list-group-item ul {
            padding-left: 1.5rem;
            margin-bottom: 0;
        }
        .list-group-item ul li {
            margin-bottom: 0.25rem;
        }
        .modal-body {
            padding: 2rem;
        }
        .modal-footer {
            border-top: none;
            padding: 1rem 2rem 2rem;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Vista de usuarios cargada.');
    </script>
@stop

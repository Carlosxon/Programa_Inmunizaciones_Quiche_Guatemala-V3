@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <h1>Crear Usuario</h1>
@stop
 

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Formulario de Creación de Usuario</h3>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                </div>
                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <!-- Campo para seleccionar bodegas -->
                <div class="form-group">
                    <label for="warehouse_ids">Bodega(s)</label>
                    <select name="warehouse_ids[]" id="warehouse_ids" class="form-control" multiple>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ in_array($warehouse->id, old('warehouse_ids', [])) ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                

                <div class="form-group">
                    <label for="role">Rol</label>
                    <div class="input-group">
                        <select name="role" class="form-control" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <a href="{{ route('roles.create') }}" class="btn btn-primary">Crear Rol</a>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="permissions">Permisos</label>
                    <div class="checkbox">
                        @foreach($permissions as $permission)
                            <label>
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                                {{ $permission->name }}
                            </label><br>
                        @endforeach
                    </div>
                    <a href="{{ route('permissions.create') }}" class="btn btn-primary mt-2">Crear Permiso</a>
                </div>

                <button type="submit" class="btn btn-success">Crear Usuario</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

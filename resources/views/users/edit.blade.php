@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar Usuario</h1>
@stop

@section('content')
<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Mensajes de error globales -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    

    <div class="form-group">
        <label for="name">Nombre</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="phone">Teléfono</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="address">Dirección</label>
        <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control" required>
    </div>

    <div class="form-group">
    <label for="password">Nueva Contraseña</label>
    <input type="password" name="password" class="form-control">
    <small class="form-text text-muted">Deja en blanco si no deseas cambiar la contraseña.</small>
    </div>

    <div class="form-group">
    <label for="password_confirmation">Confirmar Contraseña</label>
    <input type="password" name="password_confirmation" class="form-control">
    </div>


    <!-- Campo para seleccionar bodegas -->
    <dclass="form-group">
        <label for="warehouse_ids">Bodega(s)</label>
        <select name="warehouse_ids[]" id="warehouse_ids" class="form-control" multiple>
            @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" 
                    {{ in_array($warehouse->id, old('warehouse_ids', $user->warehouses->pluck('id')->toArray())) ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="roles">Roles</label>
        <select name="roles[]" class="form-control" multiple required>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="permissions">Permisos</label>
        <div class="checkbox">
            @foreach($permissions as $permission)
                <label>
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                           {{ in_array($permission->id, old('permissions', $user->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                    {{ $permission->name }}
                </label><br>
            @endforeach
        </div>
    </div>

    <button type="submit" class="btn btn-success">Actualizar Usuario</button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@stop

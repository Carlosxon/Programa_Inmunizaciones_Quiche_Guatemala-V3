@extends('adminlte::page')

@section('title', 'Gestionar Roles y Permisos')

@section('content_header')
    <h1>Gestionar Roles y Permisos para {{ $user->name }}</h1>
@stop

@section('content')
    <form action="{{ route('users.updateRolesAndPermissions', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="roles">Seleccionar Roles:</label>
            <select name="roles[]" id="roles" class="form-control" multiple>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-4">
            <label>Seleccionar Permisos:</label>
            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                {{ in_array($permission->name, $userPermissions) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $permission->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Actualizar Roles y Permisos</button>
    </form>
@stop

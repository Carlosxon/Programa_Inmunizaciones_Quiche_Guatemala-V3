@extends('adminlte::page')

@section('title', 'Detalles del Usuario')

@section('content_header')
    <h1>Detalles del Usuario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Nombre</label>
                <p>{{ $user->name }}</p>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <p>{{ $user->email }}</p>
            </div>
            <div class="form-group">
                <label for="phone">Teléfono</label>
                <p>{{ $user->phone }}</p>
            </div>
            <div class="form-group">
                <label for="address">Dirección</label>
                <p>{{ $user->address }}</p>
            </div>
            <!--a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Editar</¡a-->
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <!--button type="submit" class="btn btn-danger">Eliminar</button-->
            </form>
        </div>
    </div>
@stop

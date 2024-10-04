@extends('adminlte::page')

@section('title', 'Crear Bodega')

@section('content_header')
    <h1>Crear Nueva Bodega</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('warehouses.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Ingresar el nombre de la bodega" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location">Ubicación</label>
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" placeholder="Ingresar la ubicación de la bodega" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nuevo campo de filtro para el tipo de bodega -->
                        <div class="form-group">
                            <label for="type">Tipo de Bodega</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="">Seleccionar tipo</option>
                                <option value="regular" {{ old('type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="premium" {{ old('type') == 'premium' ? 'selected' : '' }}>Premium</option>
                                <!-- Agrega más opciones según sea necesario -->
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo de filtro adicional si es necesario -->
                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">Seleccionar estado</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                <!-- Agrega más opciones si es necesario -->
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Formulario de crear bodega cargado.'); </script>
@stop

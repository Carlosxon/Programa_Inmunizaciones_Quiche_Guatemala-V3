@extends('adminlte::page')

@section('title', 'Editar Bodega')

@section('content_header')
    <h1>Editar Bodega</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $warehouse->name) }}" placeholder="Ingresar el nombre de la bodega">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location">Ubicación</label>
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $warehouse->location) }}" placeholder="Ingresar la ubicación de la bodega">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Tipo de Bodega</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                <option value="">Seleccionar tipo</option>
                                <option value="regular" {{ old('type', $warehouse->type) == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="premium" {{ old('type', $warehouse->type) == 'premium' ? 'selected' : '' }}>Premium</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Estado</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">Seleccionar estado</option>
                                <option value="active" {{ old('status', $warehouse->status) == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status', $warehouse->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1 class="text-center"><b>DISTRIBUCIÓN DE INSUMOS / PROGRAMA DE INMUNIZACIONES/ QUICHÉ</b></h1>
@stop

@section('content')
    <h5 class="text-center">¡Bienvenido! <b> {{Auth::user()->name}}</b> desde aqui puedes administrar tus consultas y pendientes.</h5>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
    <style>
        #floating-footer {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(145deg, #3a3f44, #292d32);
            color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 1050;
            transition: all 0.3s ease;
            max-width: 300px;
        }
        #floating-footer:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        #floating-footer strong {
            display: block;
            margin-bottom: 10px;
            font-size: 1.1em;
        }
        #floating-footer a {
            display: inline-block;
            background-color: #25D366;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        #floating-footer a:hover {
            background-color: #128C7E;
            transform: scale(1.05);
        }
        #toggle-footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #25D366;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            z-index: 1051;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            animation: pulse 2s infinite;
        }
        #toggle-footer:hover {
            background: #128C7E;
            transform: scale(1.1) rotate(15deg);
            animation: none;
        }
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }
    </style>
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">
        {{-- Contenido existente de AdminLTE --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif
    </div>

    {{-- Footer Flotante Mejorado --}}
    <div id="floating-footer" style="display: none;">
        <strong>Programa de Inmunizaciones Quiché v3.0</strong>
        <a href="https://wa.me/50251802332" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-whatsapp"></i> Contactar Soporte
        </a>
    </div>

    <button id="toggle-footer" title="Información y Soporte">
        <i class="fas fa-info"></i>
    </button>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var footer = document.getElementById('floating-footer');
        var toggleButton = document.getElementById('toggle-footer');
        
        toggleButton.addEventListener('click', function(e) {
            e.stopPropagation(); // Previene que el clic se propague al documento
            if (footer.style.display === 'none' || footer.style.display === '') {
                footer.style.display = 'block';
                toggleButton.style.display = 'none';
            } else {
                footer.style.display = 'none';
                toggleButton.style.display = 'block';
            }
        });

        footer.addEventListener('click', function(e) {
            e.stopPropagation(); // Previene que el clic en el footer lo cierre inmediatamente
        });

        document.addEventListener('click', function() {
            footer.style.display = 'none';
            toggleButton.style.display = 'block';
        });
    });
    </script>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
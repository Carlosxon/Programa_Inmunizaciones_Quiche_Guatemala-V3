<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Distribución del Programa de Inmunizaciones</title>
        <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            :root {
                --primary-color: #0077be;
                --secondary-color: #00a8e8;
                --accent-color: #f9a826;
                --bg-color: #f0f4f8;
            }
            
            body {
                font-family: 'Nunito', sans-serif;
                background-color: var(--bg-color);
                overflow-x: hidden;
            }
            
            .navbar {
                background-color: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            
            .navbar-scrolled {
                background-color: rgba(255, 255, 255, 0.98);
            }
            
            .navbar-brand, .nav-link {
                color: var(--primary-color) !important;
            }
            
            .hero {
                position: relative;
                height: 100vh;
                display: flex;
                align-items: center;
                overflow: hidden;
            }
            
            .hero::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 80%;
                height: 80%;
                background: url('{{ asset('vendor/adminlte/dist/img/PIQUICHE3.jpg') }}') no-repeat center center;
                background-size: cover;
                transform: translate(-50%, -50%);
                filter: brightness(0.7);
                z-index: -1;
                animation: floatImage 20s ease-in-out infinite alternate;
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
                box-shadow: 0 0 50px rgba(0, 0, 0, 0.3);
            }
            
            @keyframes floatImage {
                0% {
                    border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
                    transform: translate(-50%, -50%) rotate(0deg);
                }
                50% {
                    border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
                }
                100% {
                    border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
                    transform: translate(-50%, -50%) rotate(5deg);
                }
            }
            
            .hero-content {
                background: rgba(255, 255, 255, 0.9);
                padding: 3rem;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                animation: fadeInUp 1s ease-out;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .features {
                padding: 100px 0;
                background: linear-gradient(135deg, var(--bg-color) 0%, #e0e8f0 100%);
            }
            
            .feature-card {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                overflow: hidden;
                position: relative;
            }
            
            .feature-card::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, var(--accent-color) 0%, transparent 70%);
                opacity: 0;
                transition: all 0.5s ease;
            }
            
            .feature-card:hover::before {
                opacity: 0.1;
                transform: scale(1.2);
            }
            
            .feature-card:hover {
                transform: translateY(-10px) scale(1.02);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            }
            
            .feature-icon {
                font-size: 3rem;
                color: var(--accent-color);
                margin-bottom: 1rem;
                transition: all 0.3s ease;
            }
            
            .feature-card:hover .feature-icon {
                transform: scale(1.1) rotate(10deg);
            }
            
            .btn-custom {
                background-color: var(--accent-color);
                color: white;
                border: none;
                padding: 10px 30px;
                border-radius: 50px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            
            .btn-custom::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.3), transparent);
                transition: all 0.5s ease;
            }
            
            .btn-custom:hover::before {
                left: 100%;
            }
            
            .btn-custom:hover {
                background-color: var(--secondary-color);
                transform: scale(1.05);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            
            footer {
                background-color: var(--primary-color);
                color: white;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">Programa de Inmunizaciones Quiché</a>
                <div class="d-flex">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-custom me-2">Inicio</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-custom me-2">Iniciar Sesión</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">Registrarse</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <header class="hero">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="hero-content text-center">
                            <h1 class="display-4 mb-4">Sistema de Distribución del Programa de Inmunizaciones</h1>
                            <p class="lead mb-5">Optimizando la distribución de insumos para proteger la salud de nuestra comunidad con tecnología de vanguardia</p>
                            <a href="{{ route('register') }}" class="btn btn-custom btn-lg">Únete a nuestro equipo</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="features">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-syringe"></i>
                            </div>
                            <h3>Gestión Eficiente</h3>
                            <p>Control preciso de inventarios y distribución de vacunas</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>Análisis Predictivo</h3>
                            <p>Estadísticas y reportes con modelos de aprendizaje automático para anticipar necesidades</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3>Colaboración Inteligente</h3>
                            <p>Plataforma integrada con asistentes virtuales para optimizar la coordinación del equipo</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="py-4">
            <div class="container text-center">
                <p>&copy; 2024 Programa de Inmunizaciones Quiché. <br> Desarrollo Comunitario (versión 3).</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    </body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mon Portofolio</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">Mon Portfolio</a>
                <div class="navbar-collapse" id="navMain">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">À propos</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('skills') }}"><i class="bi bi-tools me-1" aria-hidden="true"></i>Compétences</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('cv') }}">CV PDF</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('projects') }}">Projets</a></li>

                       
                        
                        @auth
                            @if(auth()->user()->is_admin ?? false)
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.projects.index') }}">Admin</a></li>
                            @endif
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Se connecter</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-4">
        @hasSection('header')
            <div class="mb-4">
                @yield('header')
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-dark text-white mt-auto">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <a class="navbar-brand text-white" href="{{ url('/') }}">Portfolio Amour</a>
                    <p class="small mb-0">Développeur Web & Mobile — Amour Govoetchan</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="text-white">Liens</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about') }}" class="text-white">À propos</a></li>
                        <li><a href="{{ route('skills') }}" class="text-white">Compétences</a></li>
                        <li><a href="{{ route('cv') }}" class="text-white">CV PDF</a></li>
                        <li><a href="{{ route('projects') }}" class="text-white">Projets</a></li>
                    </ul>
                        
                        
                        
                </div>
                <div class="col-md-4">
                    <h6 class="text-white"></h6>
                     <p>
        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=amoursedjro47@gmail.com"
           target="_blank"
           class="btn btn-outline-primary">
           M'écrire par e‑mail
        </a>
    </p>
                    <p class="small mb-2">Suivez-moi:</p>
                    <p class="mb-0">
                        <a href="https://www.facebook.com/beaudelaire.amor" target="_blank" rel="noopener" class="text-white me-3"><i class="bi bi-facebook" aria-hidden="true"></i> Facebook</a>
                        <a href="https://www.linkedin.com/in/amour-govoetchan-3011a53a1" target="_blank" rel="noopener" class="text-white me-3"><i class="bi bi-linkedin" aria-hidden="true"></i> LinkedIn</a>
                        <a href="https://wa.me/0154350003" target="_blank" rel="noopener" class="text-white"><i class="bi bi-whatsapp" aria-hidden="true"></i> WhatsApp</a>
                    </p>
                </div>
            </div>
            <hr class="border-top border-light my-3">
            <div class="text-center small">© {{ date('Y') }} Mon Portfolio - Tous droits réservés</div>
        </div>
    </footer>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

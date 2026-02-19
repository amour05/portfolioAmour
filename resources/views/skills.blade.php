@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5">💡 Mes Compétences</h2>

    <div class="row g-4">
        <!-- Langages -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-code-slash display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Langages</h5>
                    <p class="card-text">C, Dart (Flutter), JavaScript</p>
                </div>
            </div>
        </div>

        <!-- Web & Mobile -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-phone display-4 text-success mb-3"></i>
                    <h5 class="card-title">Web & Mobile</h5>
                    <p class="card-text">HTML5, Blade, CSS3, Bootstrap, PHP, Flutter</p>
                </div>
            </div>
        </div>

        <!-- Frameworks & CMS -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-layers display-4 text-warning mb-3"></i>
                    <h5 class="card-title">Frameworks & CMS</h5>
                    <p class="card-text">Laravel, React.js, Next.js, WordPress</p>
                </div>
            </div>
        </div>

        <!-- Bases de données -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-database display-4 text-danger mb-3"></i>
                    <h5 class="card-title">Bases de données</h5>
                    <p class="card-text">MySQL, SGBD</p>
                </div>
            </div>
        </div>

        <!-- Outils & Environnements -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-tools display-4 text-info mb-3"></i>
                    <h5 class="card-title">Outils & Environnements</h5>
                    <p class="card-text">Laragon, XAMPP, Android Studio, VS Code, Figma</p>
                </div>
            </div>
        </div>

        <!-- Graphisme & Bureautique -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-palette display-4 text-secondary mb-3"></i>
                    <h5 class="card-title">Graphisme & Bureautique</h5>
                    <p class="card-text">Canva, Word, Excel</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

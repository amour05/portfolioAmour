@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <h1 class="mb-3"> À propos de moi</h1>
            <p class="lead mb-4">
                Je m’appelle <strong>Amour Govoetchan</strong>, développeur web & mobile, passionné de technologie, de digital et d’innovation. 
                Titulaire d’une Licence en Génie Informatique, j’accompagne les particuliers, startups et entreprises dans la conception de solutions numériques 
                efficaces, modernes et orientées résultats.
            </p>

            <div class="row align-items-center mb-4">
                <div class="col-md-4 text-center">
                    <img src="{{ asset('images/me.jpg') }}" alt="Photo de Amour Govoetchan" class="img-fluid rounded shadow-sm" style="max-width:250px;">
                    <p class="small text-muted mt-2"></p>
                </div>
                <div class="col-md-8">
                    <p class="mb-0">
                        Bonjour ! Voici une photo récente de moi.
                    </p>
                    <p class="mt-2 mb-0 small text-muted">
                        
                    </p>
                </div>
            </div>
            <p class="mb-4">
                Depuis toujours, je crois que la technologie n’a de valeur que lorsqu’elle résout de vrais problèmes. 
                C’est cette vision qui guide chacun de mes projets : comprendre les besoins, proposer des solutions claires 
                et créer des outils utiles, performants et évolutifs.
            </p>

            <hr class="my-4">

            <h2 class="h4 mb-3">Ce que je fais</h2>
            <p class="mb-3">
                Je conçois et développe des solutions digitales sur mesure, allant du simple site vitrine aux applications web et mobiles plus complexes.
            </p>

            <h3 class="h5 mt-3 mb-2">🔹Développement web & mobile</h3>
            <ul class="list-unstyled mb-3">
                <li class="mb-2"><strong>Sites modernes:</strong> Création de sites web modernes, responsives et performants</li>
                <li class="mb-2"><strong>Apps web & mobile:</strong> Développement d’applications web et mobiles</li>
                <li class="mb-2"><strong>Intégration UI/UX:</strong> Intégration fidèle aux maquettes et aux parcours utilisateurs</li>
                <li class="mb-2"><strong>Maintenance:</strong> Amélioration continue et optimisation de solutions existantes</li>
            </ul>
            <p class="mb-4">
                <strong>Technologies:</strong> HTML, CSS, Bootstrap, JavaScript, PHP, Laravel, Flutter, React, Next.js, WordPress, MySQL.
            </p>

            <h3 class="h5 mt-3 mb-2"> 🔹Marketing digital & communication</h3>
            <p class="mb-2">
                Parce qu’un bon produit mérite d’être visible, j’intègre également une dimension marketing et communication digitale à mes services :
            </p>
            <ul class="list-unstyled mb-4">
                <li class="mb-2"><strong>Community management:</strong> Animation et gestion des communautés</li>
                <li class="mb-2"><strong>Contenus:</strong> Création de contenus engageants</li>
                <li class="mb-2"><strong>Stratégie réseaux:</strong> Visibilité et performance sur les réseaux sociaux</li>
                <li class="mb-2"><strong>Visuels:</strong> Conception de visuels digitaux (Canva)</li>
            </ul>
            <p class="mb-4">
                Mon objectif est d’aider les marques et projets à se démarquer, attirer et fidéliser leur audience.
            </p>

            <hr class="my-4">

            <h2 class="h4 mb-3">🔹Ma philosophie de travail</h2>
            <ul class="list-unstyled mb-4">
                <li class="mb-2"><strong>Comprendre avant de coder:</strong> Analyser le besoin réel et le contexte d’usage</li>
                <li class="mb-2"><strong>Simplicité & qualité:</strong> Privilégier des solutions claires, robustes et efficaces</li>
                <li class="mb-2"><strong>Orientation utilisateur:</strong> Concevoir pour l’expérience et la valeur</li>
                <li class="mb-2"><strong>Apprentissage continu:</strong> Veille technologique et amélioration permanente</li>
            </ul>
            <p class="mb-4">
                Je suis quelqu’un de curieux, rigoureux et orienté solutions, avec un réel plaisir à transformer une idée en produit concret.
            </p>

            <hr class="my-4">

            <h2 class="h4 mb-3">🔹Pourquoi travailler avec moi ?</h2>
            <ul class="list-unstyled mb-4">
                <li class="mb-2"><strong>Approche humaine:</strong> Écoute, clarté et accompagnement personnalisé</li>
                <li class="mb-2"><strong>Double compétence:</strong> Technique & digitale, du produit à sa visibilité</li>
                <li class="mb-2"><strong>Suivi complet:</strong> De l’idée au déploiement, avec transparence</li>
                <li class="mb-2"><strong>Implication réelle:</strong> Chaque projet est une collaboration, pas juste une mission</li>
            </ul>

            <hr class="my-4">

            <h2 class="h4 mb-3">Travaillons ensemble</h2>
            <p class="mb-3">
                Vous avez une idée, un projet ou un besoin digital ? Je suis disponible pour des collaborations, missions freelance ou opportunités professionnelles.
            </p>
            <p class="mb-4">
                 N’hésitez pas à me contacter, je serai ravi d’échanger avec vous.
            </p>

            <div class="d-flex flex-wrap gap-2">
                 <a href="https://wa.me/2290154350003" target="_blank" class="btn btn-success">
        <i class="fab fa-whatsapp"></i> Me contacter sur WhatsApp
    </a>
                <a href="https://github.com/amour05" target="_blank" rel="noopener" class="btn btn-outline-dark">Mon GitHub</a>
            </div>

        </div>
    </div>
</div>
@endsection

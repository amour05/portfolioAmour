@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">✏️ Modifier le projet</h2>

    {{-- Affichage des erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre du projet</label>
            <input type="text" class="form-control" id="title" name="title" 
                   value="{{ old('title', $project->title) }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $project->description) }}</textarea>
        </div>

        <!-- Type -->
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="site" {{ old('type', $project->type) == 'site' ? 'selected' : '' }}>Site Web</option>
                <option value="app" {{ old('type', $project->type) == 'app' ? 'selected' : '' }}>Application</option>
                <option value="graphisme" {{ old('type', $project->type) == 'graphisme' ? 'selected' : '' }}>Graphisme</option>
            </select>
        </div>

        <!-- Langages -->
        <div class="mb-3">
            <label for="langages" class="form-label">Langages utilisés</label>
            <input type="text" class="form-control" id="langages" name="langages" 
                   value="{{ old('langages', $project->langages) }}" placeholder="Ex: PHP, JavaScript, Dart">
        </div>

        <!-- Framework -->
        <div class="mb-3">
            <label for="framework" class="form-label">Framework</label>
            <input type="text" class="form-control" id="framework" name="framework" 
                   value="{{ old('framework', $project->framework) }}" placeholder="Ex: Laravel, Flutter">
        </div>

        <!-- Outils -->
        <div class="mb-3">
            <label for="outils" class="form-label">Outils</label>
            <input type="text" class="form-control" id="outils" name="outils" 
                   value="{{ old('outils', $project->outils) }}" placeholder="Ex: Docker, GitHub, VSCode">
        </div>

        <!-- Environnement -->
        <div class="mb-3">
            <label for="environnement" class="form-label">Environnement</label>
            <input type="text" class="form-control" id="environnement" name="environnement" 
                   value="{{ old('environnement', $project->environnement) }}" placeholder="Ex: Windows, Linux, Render">
        </div>

        <!-- Base de données -->
        <div class="mb-3">
            <label for="database" class="form-label">Base de données</label>
            <input type="text" class="form-control" id="database" name="database" 
                   value="{{ old('database', $project->database) }}" placeholder="Ex: MySQL, PostgreSQL, Firebase">
        </div>

        <!-- Lien code source -->
        <div class="mb-3">
            <label for="source_link" class="form-label">Lien vers le code source</label>
            <input type="url" class="form-control" id="source_link" name="source_link" 
                   value="{{ old('source_link', $project->source_link) }}" 
                   placeholder="https://github.com/...">
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection

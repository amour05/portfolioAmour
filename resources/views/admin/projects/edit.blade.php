@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">✏️ Modifier le projet</h2>

    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre du projet</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $project->title) }}" required>
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

        <!-- Image -->
        <div class="mb-3">
            <label for="image" class="form-label">Image du projet</label>
            @if($project->image)
                @php
                    $currentSrc = \Illuminate\Support\Str::startsWith($project->image, ['http://','https://']) ? $project->image : asset('storage/'.$project->image);
                @endphp
                <div class="mb-2">
                    <img src="{{ $currentSrc }}" width="120" alt="Image actuelle">
                </div>
            @endif
            <input type="file" class="form-control" id="image" name="image">
            <small class="text-muted">Laisser vide si tu ne veux pas changer l'image.</small>
        </div>

        <!-- Lien code source -->
        <div class="mb-3">
            <label for="source_link" class="form-label">Lien vers le code source</label>
            <input type="url" class="form-control" id="source_link" name="source_link" value="{{ old('source_link', $project->source_link) }}" placeholder="https://github.com/...">
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection

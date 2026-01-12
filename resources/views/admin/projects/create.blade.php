@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">➕ Ajouter un projet</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre du projet</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
        </div>

        <!-- Type -->
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="site" {{ old('type')=='site' ? 'selected' : '' }}>Site Web</option>
                <option value="app" {{ old('type')=='app' ? 'selected' : '' }}>Application</option>
                <option value="graphisme" {{ old('type')=='graphisme' ? 'selected' : '' }}>Graphisme</option>
            </select>
        </div>

        <!-- Image -->
        <div class="mb-3">
            <label for="image" class="form-label">Image du projet</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>

        <!-- Lien code source -->
        <div class="mb-3">
            <label for="source_link" class="form-label">Lien vers le code source</label>
            <input type="url" class="form-control" id="source_link" name="source_link" value="{{ old('source_link') }}" placeholder="https://github.com/...">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection

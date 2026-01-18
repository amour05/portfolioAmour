@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">➕ Nouvel article</h2>

    <form action="{{ route('admin.posts.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <textarea name="content" class="form-control" rows="8" required></textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="published" class="form-check-input" checked>
            <label class="form-check-label">Publié</label>
        </div>

        <button type="submit" class="btn btn-success">Créer</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection

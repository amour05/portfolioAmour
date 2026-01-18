@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">✏️ Modifier l’article</h2>

    <form action="{{ route('admin.posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" class="form-control" value="{{ $post->title }}" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <textarea name="content" class="form-control" rows="8" required>{{ $post->content }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="published" class="form-check-input" {{ $post->published ? 'checked' : '' }}>
            <label class="form-check-label">Publié</label>
        </div>

        <button type="submit" class="btn btn-warning">Mettre à jour</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection

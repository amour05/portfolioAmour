@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">📚 Gestion des articles</h2>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary mb-3">➕ Nouvel article</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Publié</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->published ? '✅ Oui' : '❌ Non' }}</td>
                    <td>
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-warning">✏️ Modifier</a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet article ?')">🗑️ Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $posts->links() }}
</div>
@endsection

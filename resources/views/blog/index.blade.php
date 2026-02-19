@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5">📚 Blog</h2>

    <div class="row g-4">
        @forelse($posts as $post)
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ Str::limit($post->content, 150) }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary btn-sm">
                            Lire la suite →
                        </a>
                    </div>
                    <div class="card-footer text-muted">
                        Publié le {{ $post->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Aucun article disponible pour le moment.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection

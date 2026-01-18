@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">{{ $post->title }}</h2>
    <p class="text-muted">Publié le {{ $post->created_at->format('d/m/Y') }}</p>

    <div class="mb-4">
        {!! nl2br(e($post->content)) !!}
    </div>

    <a href="{{ route('blog.index') }}" class="btn btn-secondary">← Retour au blog</a>
</div>
@endsection

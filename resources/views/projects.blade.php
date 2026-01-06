@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5">🚀 Mes Projets</h2>

    <div class="row g-4">
        @forelse($projects as $project)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    {{-- Image du projet --}}
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" class="card-img-top" alt="{{ $project->title }}">
                    @else
                        <img src="/images/projets/default.png" class="card-img-top" alt="{{ $project->title }}">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $project->title }}</h5>
                        <p class="card-text">
                            {{ Str::limit($project->description, 120) }}
                        </p>
                    </div>

                    <div class="card-footer text-center bg-white">
                        @if($project->source_link)
                            <a href="{{ $project->source_link }}" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-github"></i> Code source
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">Aucun projet disponible pour le moment.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination si tu veux limiter le nombre par page --}}
    <div class="mt-4">
        {{ $projects->links() }}
    </div>
</div>
@endsection

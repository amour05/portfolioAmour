@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5">🚀 Mes Projets</h2>

    <div class="row g-4">
        @forelse($projects as $project)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">{{ $project->title }}</h5>
                        <p class="card-text">
                            {{ Str::limit($project->description, 120) }}
                        </p>

                        <!-- Badges techniques -->
                        <div class="mt-3">
                            @if($project->langages)
                                <span class="badge bg-primary me-1">{{ $project->langages }}</span>
                            @endif
                            @if($project->framework)
                                <span class="badge bg-success me-1">{{ $project->framework }}</span>
                            @endif
                            @if($project->outils)
                                <span class="badge bg-info text-dark me-1">{{ $project->outils }}</span>
                            @endif
                            @if($project->environnement)
                                <span class="badge bg-warning text-dark me-1">{{ $project->environnement }}</span>
                            @endif
                            @if($project->database)
                                <span class="badge bg-danger me-1">{{ $project->database }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer text-center bg-white">
                        @if($project->source_link)
                            <a href="{{ $project->source_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
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

    {{-- Pagination si nécessaire --}}
    <div class="mt-4">
        {{ $projects->links() }}
    </div>
</div>
@endsection

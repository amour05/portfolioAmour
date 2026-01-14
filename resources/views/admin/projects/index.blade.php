@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📂 Mes Projets</h2>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary mb-3">+ Ajouter un projet</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Type</th>
                <th>Langages</th>
                <th>Framework</th>
                <th>Outils</th>
                <th>Environnement</th>
                <th>Base de données</th>
                <th>Source</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
            <tr>
                <td>{{ $project->title }}</td>
                <td>{{ ucfirst($project->type) }}</td>
                <td>{{ $project->langages ?? '—' }}</td>
                <td>{{ $project->framework ?? '—' }}</td>
                <td>{{ $project->outils ?? '—' }}</td>
                <td>{{ $project->environnement ?? '—' }}</td>
                <td>{{ $project->database ?? '—' }}</td>
                <td>
                    @if($project->source_link)
                        <a href="{{ $project->source_link }}" target="_blank">Voir code</a>
                    @else
                        <span class="text-muted">Non fourni</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.projects.edit',$project->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('admin.projects.destroy',$project->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce projet ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted">Aucun projet enregistré</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

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
                <th>Image</th>
                <th>Source</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>{{ $project->title }}</td>
                <td>{{ ucfirst($project->type) }}</td>
                <td>
                    @if($project->image)
                        <img src="{{ asset('storage/'.$project->image) }}" width="80">
                    @endif
                </td>
                <td>
                    @if($project->source_link)
                        <a href="{{ $project->source_link }}" target="_blank">Voir code</a>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.projects.edit',$project->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('admin.projects.destroy',$project->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

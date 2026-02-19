@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <h1 class="mb-3">📄 Mon CV</h1>
            <p class="lead mb-4">
                Vous pouvez consulter mon CV en ligne ou le télécharger en PDF pour le garder.
            </p>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ asset('cv_amour.pdf') }}" target="_blank" class="btn btn-primary">
                    Voir le CV
                </a>
                <a href="{{ asset('cv_amour.pdf') }}" download class="btn btn-success">
                    Télécharger le CV
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

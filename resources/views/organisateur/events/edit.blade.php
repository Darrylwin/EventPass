@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-3xl font-bold font-display">Modifier l'événement</h1>
            <a href="{{ route('organisateur.events.index') }}" class="text-sm text-muted-foreground hover:text-foreground">
                Retour à la liste
            </a>
        </div>

        <div class="bg-card border border-border rounded-xl p-6">
            <form method="POST" action="{{ route('organisateur.events.update', $event) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                @include('organisateur.events.partials.form', ['event' => $event])

                <button
                    type="submit"
                    class="bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition"
                >
                    Mettre à jour
                </button>
            </form>
        </div>
    </div>
@endsection

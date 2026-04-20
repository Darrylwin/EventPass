@extends('layouts.app')

@section('title', 'Créer un événement — ' . config('app.name'))

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div>
            <nav class="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                <a href="{{ route('organisateur.events.index') }}" class="hover:text-foreground transition-colors">
                    Mes événements
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-foreground">Créer</span>
            </nav>
            <h1 class="text-2xl font-bold font-display">Créer un événement</h1>
        </div>

        <div class="bg-card border border-border rounded-xl p-6">
            <form method="POST" action="{{ route('organisateur.events.store') }}"
                  enctype="multipart/form-data" class="space-y-5">
                @csrf
                @include('organisateur.events.partials.form', ['event' => $event])

                <div class="flex items-center gap-3 pt-2 border-t border-border">
                    <button type="submit"
                            class="bg-primary text-primary-foreground px-5 py-2.5 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                        Créer l'événement
                    </button>
                    <a href="{{ route('organisateur.events.index') }}"
                       class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>

    </div>
@endsection

@extends('layouts.app')

@section('title', 'Modifier — ' . $event->title)

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
                <a href="{{ route('organisateur.events.show', $event) }}"
                   class="hover:text-foreground transition-colors truncate max-w-xs">
                    {{ $event->title }}
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-foreground">Modifier</span>
            </nav>
            <h1 class="text-2xl font-bold font-display">Modifier l'événement</h1>
        </div>

        <div class="bg-card border border-border rounded-xl p-6">
            <form method="POST" action="{{ route('organisateur.events.update', $event) }}"
                  enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                @include('organisateur.events.partials.form', ['event' => $event])

                <div class="flex items-center justify-between pt-2 border-t border-border">
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="bg-primary text-primary-foreground px-5 py-2.5 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                            Enregistrer les modifications
                        </button>
                        <a href="{{ route('organisateur.events.show', $event) }}"
                           class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            Annuler
                        </a>
                    </div>

                    <form method="POST" action="{{ route('organisateur.events.destroy', $event) }}"
                          onsubmit="return confirm('Supprimer définitivement cet événement ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-xs px-3 py-2 rounded-md border border-destructive/40 text-destructive hover:bg-destructive/10 transition-colors">
                            Supprimer
                        </button>
                    </form>
                </div>
            </form>
        </div>

    </div>
@endsection

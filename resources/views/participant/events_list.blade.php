@extends('layouts.app')

@section('title', 'Événements — ' . config('app.name'))

@section('content')
    <div class="max-w-6xl mx-auto space-y-8">

        {{-- En-tête --}}
        <div class="space-y-1">
            <h1 class="text-2xl font-bold font-display">Événements</h1>
            <p class="text-sm text-muted-foreground">Découvrez et inscrivez-vous aux événements à venir.</p>
        </div>

        @php
            // Les collections sont pré-calculées côté contrôleur lorsque la pagination est activée
            $upcomingEvents = $upcomingEvents ?? collect();
            $pastEvents = $pastEvents ?? collect();
        @endphp

        {{-- Événements à venir --}}
        @if($upcomingEvents->isNotEmpty())
            <section class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground">
                        À venir <span class="text-foreground">({{ $upcomingEvents->count() }})</span>
                    </h2>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($upcomingEvents as $event)
                        @include('participant.partials.event-card', ['event' => $event, 'isPast' => false])
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Séparateur si les deux sections existent --}}
        @if($upcomingEvents->isNotEmpty() && $pastEvents->isNotEmpty())
            <div class="flex items-center gap-4">
                <div class="flex-1 border-t border-border"></div>
                <span
                    class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Événements passés</span>
                <div class="flex-1 border-t border-border"></div>
            </div>
        @endif

        {{-- Événements passés --}}
        @if($pastEvents->isNotEmpty())
            <section class="space-y-4">
                @if($upcomingEvents->isEmpty())
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground">
                            Passés <span class="text-foreground">({{ $pastEvents->count() }})</span>
                        </h2>
                    </div>
                @endif

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($pastEvents as $event)
                        @include('participant.partials.event-card', ['event' => $event, 'isPast' => true])
                    @endforeach
                </div>
            </section>
        @endif

        {{-- État vide --}}
        @if($events->isEmpty())
            <div class="py-24 text-center">
                <div class="w-14 h-14 bg-muted rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-muted-foreground">Aucun événement disponible pour le moment.</p>
            </div>
        @endif

    </div>
@endsection

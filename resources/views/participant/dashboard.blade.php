@extends('layouts.app')

@section('title', 'Mes inscriptions — ' . config('app.name'))

@section('content')
    <div class="max-w-5xl mx-auto space-y-8">

        {{-- En-tête --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold font-display">Mes inscriptions</h1>
                <p class="text-sm text-muted-foreground mt-1">
                    Retrouvez vos pass et l'historique de vos événements.
                </p>
            </div>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-sm font-medium bg-primary text-primary-foreground px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Explorer les événements
            </a>
        </div>

        {{-- État vide global --}}
        @if($registrations->isEmpty())
            <div class="bg-card border border-dashed border-border rounded-2xl py-20 text-center">
                <div class="max-w-sm mx-auto space-y-5 px-4">
                    <div class="w-16 h-16 bg-muted rounded-2xl flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8 text-muted-foreground" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Aucun pass pour le moment</h2>
                        <p class="text-muted-foreground text-sm mt-1">
                            Inscrivez-vous à un événement pour recevoir votre pass numérique.
                        </p>
                    </div>
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-5 py-2.5 rounded-xl font-medium text-sm hover:opacity-90 transition-opacity">
                        Voir les événements disponibles
                    </a>
                </div>
            </div>

        @else

            {{-- Section : Événements à venir --}}
            @if($upcoming->isNotEmpty())
                <section class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <h2 class="text-base font-semibold">À venir <span
                                class="text-muted-foreground font-normal text-sm">({{ $upcoming->count() }})</span></h2>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach($upcoming as $registration)
                            <div
                                class="bg-card border border-border rounded-xl overflow-hidden hover:shadow-md transition-shadow">

                                {{-- Bandeau statut --}}
                                @if($registration->status === 'annulé')
                                    <div
                                        class="bg-destructive/10 border-b border-destructive/20 px-4 py-2 flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-destructive shrink-0" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        <span class="text-xs font-medium text-destructive">Pass invalidé par l'organisateur</span>
                                    </div>
                                @endif

                                <div class="p-5 flex gap-4">
                                    {{-- Image événement --}}
                                    @if($registration->event->image_path)
                                        <img
                                            src="{{ \Illuminate\Support\Facades\Storage::url($registration->event->image_path) }}"
                                            class="w-20 h-20 object-cover rounded-lg shrink-0 border border-border"
                                            alt="{{ $registration->event->title }}">
                                    @else
                                        <div
                                            class="w-20 h-20 bg-muted rounded-lg shrink-0 flex items-center justify-center border border-border">
                                            <svg class="w-6 h-6 text-muted-foreground" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-base truncate">{{ $registration->event->title }}</h3>
                                        <div class="flex items-center gap-1 mt-1 text-xs text-muted-foreground">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $registration->event->starts_at->format('d M Y à H:i') }}
                                        </div>
                                        <div class="flex items-center gap-1 mt-0.5 text-xs text-muted-foreground">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="truncate">{{ $registration->event->location }}</span>
                                        </div>

                                        {{-- Pass numérique --}}
                                        <div class="mt-3 flex items-end justify-between gap-2">
                                            <div class="{{ $registration->status === 'annulé' ? 'opacity-50' : '' }}">
                                                <span
                                                    class="block text-[10px] uppercase font-bold tracking-widest text-muted-foreground leading-none mb-1">Votre pass</span>
                                                <span
                                                    class="font-mono text-primary font-bold tracking-widest text-xl leading-none {{ $registration->status === 'annulé' ? 'line-through text-muted-foreground' : '' }}">
                                                {{ $registration->pass_code }}
                                            </span>
                                            </div>
                                            <a href="{{ route('events.show', $registration->event) }}"
                                               class="text-xs text-muted-foreground hover:text-primary transition-colors underline-offset-2 hover:underline shrink-0">
                                                Détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Section : Historique --}}
            @if($past->isNotEmpty())
                <section class="space-y-4">
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-semibold text-muted-foreground">
                            Historique <span class="font-normal text-sm">({{ $past->count() }})</span>
                        </h2>
                    </div>

                    <div class="bg-card border border-border rounded-xl overflow-hidden divide-y divide-border">
                        @foreach($past as $registration)
                            <div
                                class="px-5 py-4 flex items-center gap-4 opacity-70 hover:opacity-100 transition-opacity">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $registration->event->title }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5">
                                        {{ $registration->event->starts_at->format('d M Y') }}
                                        &bull;
                                        {{ $registration->event->location }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span
                                        class="font-mono text-xs text-muted-foreground">{{ $registration->pass_code }}</span>
                                    <div class="mt-0.5">
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded text-[10px] font-medium bg-muted text-muted-foreground">
                                        Terminé
                                    </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

        @endif

    </div>
@endsection

@extends('layouts.app')

@section('title', $event->title . ' — ' . config('app.name'))

@section('content')
    <div class="max-w-5xl mx-auto">

        {{-- Fil d'Ariane --}}
        <nav class="flex items-center gap-2 text-sm text-muted-foreground mb-6">
            <a href="{{ route('home') }}" class="hover:text-foreground transition-colors">Événements</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-foreground truncate max-w-xs">{{ $event->title }}</span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- Colonne gauche : infos événement --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Image --}}
                <div class="aspect-video rounded-2xl overflow-hidden bg-muted">
                    @if($event->image_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($event->image_path) }}"
                             class="w-full h-full object-cover"
                             alt="{{ $event->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-muted-foreground/30" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Titre + méta --}}
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary">
                        {{ $event->isFree() ? 'Gratuit' : number_format((float)$event->price, 0, ',', ' ') . ' FCFA' }}
                    </span>
                        @if($event->starts_at->isPast())
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-muted text-muted-foreground">
                            Terminé
                        </span>
                        @elseif($event->status === 'annulé')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-destructive/10 text-destructive">
                            Annulé
                        </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold font-display leading-tight">{{ $event->title }}</h1>

                    <div class="flex flex-wrap gap-5 text-sm text-muted-foreground">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $event->starts_at->format('d M Y à H:i') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $event->location }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Par {{ $event->organizer->name }}
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-card border border-border rounded-xl p-6">
                    <h2 class="font-semibold text-base mb-3">À propos de l'événement</h2>
                    <div
                        class="text-sm text-muted-foreground leading-relaxed whitespace-pre-wrap">{{ $event->description }}</div>
                </div>

            </div>

            {{-- Colonne droite : bloc réservation sticky --}}
            <div class="lg:col-span-1">
                <div class="sticky top-20 space-y-4">
                    <div class="bg-card border border-border rounded-xl p-5 space-y-4">
                        <h2 class="font-semibold text-base">Réservation</h2>

                        {{-- Jauge de places --}}
                        @php
                            // Préférer le compteur pré-calculé `validated_registrations_count` s'il est présent
                            $validated = $event->validated_registrations_count ?? null;
                            if ($validated === null) {
                                $validated = $event->registrations()->where('status', 'validé')->count();
                            }
                            $spots = max(0, $event->capacity - $validated);
                            $fillPercent = $event->capacity > 0
                                ? min(100, round(($event->capacity - $spots) / $event->capacity * 100))
                                : 100;
                            $isAlmostFull = $spots <= 5 && $spots > 0;
                            $isVeryLow = $spots <= 2 && $spots > 0;
                        @endphp

                        @if(!$event->starts_at->isPast() && $event->status !== 'annulé')
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-muted-foreground">Places réservées</span>
                                    <span
                                        class="font-medium {{ $isVeryLow ? 'text-destructive' : ($isAlmostFull ? 'text-amber-600' : 'text-foreground') }}">
                                    {{ $event->capacity - $spots }}/{{ $event->capacity }}
                                </span>
                                </div>
                                <div class="h-2 bg-muted rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all
                                    {{ $fillPercent >= 100 ? 'bg-destructive' : ($isAlmostFull ? 'bg-amber-500' : 'bg-primary') }}"
                                         style="width: {{ $fillPercent }}%">
                                    </div>
                                </div>

                                @if($spots === 0)
                                    <p class="text-xs font-semibold text-destructive text-center pt-1">Aucune place
                                        disponible</p>
                                @elseif($isVeryLow)
                                    <p class="text-xs font-bold text-destructive text-center pt-1 animate-pulse">
                                        Plus que {{ $spots }} place{{ $spots > 1 ? 's' : '' }} !
                                    </p>
                                @elseif($isAlmostFull)
                                    <p class="text-xs font-medium text-amber-600 text-center pt-1">
                                        {{ $spots }} places restantes
                                    </p>
                                @else
                                    <p class="text-xs text-muted-foreground text-center pt-1">
                                        {{ $spots }} places disponibles
                                    </p>
                                @endif
                            </div>
                        @endif

                        {{-- Prix --}}
                        <div class="flex items-center justify-between py-3 border-t border-border">
                            <span class="text-sm text-muted-foreground">Tarif</span>
                            <span class="font-semibold text-foreground">
                            {{ $event->isFree() ? 'Gratuit' : number_format((float)$event->price, 0, ',', ' ') . ' FCFA' }}
                        </span>
                        </div>

                        {{-- Zone CTA --}}
                        @if($event->starts_at->isPast() || $event->status === 'annulé')
                            <div class="bg-muted rounded-xl px-4 py-3 text-center">
                                <p class="text-sm font-medium text-muted-foreground">
                                    {{ $event->status === 'annulé' ? 'Cet événement a été annulé.' : 'Cet événement est terminé.' }}
                                </p>
                            </div>

                        @elseif($spots === 0)
                            <div
                                class="bg-destructive/10 border border-destructive/20 rounded-xl px-4 py-3 text-center">
                                <p class="text-sm font-semibold text-destructive">Complet</p>
                                <p class="text-xs text-muted-foreground mt-1">Plus aucune place disponible.</p>
                            </div>

                        @else
                            @auth
                                @if(isset($userRegistration) && $userRegistration)
                                    {{-- Déjà inscrit --}}
                                    <div
                                        class="bg-primary/10 border border-primary/20 rounded-xl px-4 py-4 text-center space-y-2">
                                        <div class="flex items-center justify-center gap-2 text-primary">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="font-semibold text-sm">Vous êtes inscrit</span>
                                        </div>
                                        @if($userRegistration->status === 'validé')
                                            <div class="bg-background rounded-lg px-3 py-2.5 border border-border">
                                                <span
                                                    class="block text-[10px] uppercase font-bold tracking-widest text-muted-foreground leading-none mb-1.5">Votre pass</span>
                                                <span class="font-mono text-primary font-bold tracking-widest text-2xl">
                                                {{ $userRegistration->pass_code }}
                                            </span>
                                            </div>
                                            <a href="{{ route('participant.dashboard') }}"
                                               class="block text-xs text-primary hover:underline font-medium">
                                                Voir dans mon tableau de bord
                                            </a>
                                        @else
                                            <p class="text-xs text-destructive">Votre pass a été invalidé.</p>
                                        @endif
                                    </div>

                                @elseif(auth()->user()->isParticipant())
                                    {{-- Bouton d'inscription --}}
                                    <form action="{{ route('participant.events.register', $event) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="w-full bg-primary text-primary-foreground py-3.5 rounded-xl font-semibold text-sm hover:opacity-90 active:scale-[0.99] transition-all shadow-sm">
                                            S'inscrire à cet événement
                                        </button>
                                    </form>
                                    <p class="text-xs text-muted-foreground text-center">
                                        Un pass numérique unique vous sera délivré.
                                    </p>

                                @else
                                    {{-- Connecté mais pas participant (org/admin) --}}
                                    <p class="text-xs text-muted-foreground text-center bg-muted rounded-lg px-3 py-2.5">
                                        Seuls les participants peuvent s'inscrire.
                                    </p>
                                @endif

                            @else
                                {{-- Non connecté --}}
                                <div class="space-y-3">
                                    <a href="{{ route('login') }}"
                                       class="block w-full text-center bg-primary text-primary-foreground py-3.5 rounded-xl font-semibold text-sm hover:opacity-90 transition-opacity">
                                        Se connecter pour s'inscrire
                                    </a>
                                    <p class="text-xs text-muted-foreground text-center">
                                        Pas encore de compte ?
                                        <a href="{{ route('register') }}"
                                           class="text-primary hover:underline font-medium">S'inscrire gratuitement</a>
                                    </p>
                                </div>
                            @endauth
                        @endif
                    </div>

                    {{-- Retour liste --}}
                    <a href="{{ route('home') }}"
                       class="flex items-center justify-center gap-2 w-full text-sm text-muted-foreground hover:text-foreground transition-colors py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Retour aux événements
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-muted-foreground hover:text-foreground transition-colors mb-4">
        &larr; Retour aux événements
    </a>

    <div class="bg-card border border-border rounded-3xl overflow-hidden shadow-sm">
        @if($event->image_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($event->image_path) }}" 
                 class="w-full h-72 md:h-96 object-cover" alt="{{ $event->title }}">
        @else
            <div class="w-full h-72 md:h-96 bg-muted flex items-center justify-center">
                <span class="text-muted-foreground text-lg">Aucune image pour cet événement</span>
            </div>
        @endif

        <div class="p-8 md:p-12">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
                <div class="space-y-4 flex-1">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold uppercase tracking-wider text-primary bg-primary/10 px-3 py-1 rounded">
                            {{ $event->isFree() ? 'Gratuit' : number_format($event->price, 0, ',', ' ') . ' FCFA' }}
                        </span>
                        @if($event->starts_at->isPast())
                            <span class="text-sm font-bold uppercase tracking-wider bg-foreground/10 text-foreground px-3 py-1 rounded">
                                Terminé
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl font-bold font-display leading-tight">{{ $event->title }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-muted-foreground pt-2">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $event->starts_at->format('d M Y à H:i') }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $event->location }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Organisé par {{ $event->organizer->name }}
                        </div>
                    </div>
                </div>

                @if(!$event->starts_at->isPast())
                    <div class="bg-muted/50 p-6 rounded-2xl border border-border min-w-[300px] text-center">
                        <h3 class="font-bold text-lg mb-4 text-foreground text-left">Réservation</h3>
                        
                        {{-- UX Answer Q1: Alert when <= 2 --}}
                        @php
                            $spots = $event->availableSpots();
                            $hasAlert = $spots <= 2 && $spots > 0;
                        @endphp
                        
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-border text-left">
                            <span class="text-sm text-muted-foreground font-medium">Capacité totale</span>
                            <span class="font-bold">{{ $event->capacity }} places</span>
                        </div>
                        
                        <div class="mb-6">
                            @if($spots === 0)
                                <div class="bg-destructive/10 text-destructive border border-destructive/20 rounded-xl p-4 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    <span class="font-bold">Complet</span>
                                </div>
                            @else
                                <div class="{{ $hasAlert ? 'bg-destructive/10 border-destructive/30 border animate-pulse' : 'bg-primary/5 border-border border' }} p-4 rounded-xl text-center transition-colors">
                                    <span class="block text-sm font-medium {{ $hasAlert ? 'text-destructive' : 'text-muted-foreground' }} mb-1">Places disponibles</span>
                                    <span class="block text-4xl font-black font-mono {{ $hasAlert ? 'text-destructive' : 'text-foreground' }}">
                                        {{ $spots }}
                                    </span>
                                    @if($hasAlert)
                                        <div class="text-xs font-bold uppercase tracking-wider text-destructive mt-2">Plus que {{ $spots }} place{{ $spots > 1 ? 's' : '' }} !</div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @auth
                            @if(auth()->user()->registrations->contains('event_id', $event->id))
                                <div class="bg-primary/10 text-primary p-4 rounded-xl text-center space-y-2">
                                    <p class="font-bold">Vous êtes inscrit !</p>
                                    <a href="{{ route('participant.dashboard') }}" class="text-sm hover:underline font-medium">
                                        Voir mon pass dans le tableau de bord &rarr;
                                    </a>
                                </div>
                            @elseif($spots > 0)
                                <form action="{{ route('participant.events.register', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary text-primary-foreground py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                        S'inscrire à l'événement
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block text-center w-full bg-primary text-primary-foreground py-3 rounded-xl font-bold hover:opacity-90 transition-all">
                                Se connecter pour réserver
                            </a>
                        @endauth
                    </div>
                @endif
            </div>

            <div class="prose prose-lg dark:prose-invert max-w-none">
                <h3 class="text-2xl font-bold mb-4 font-display">À propos de l'événement</h3>
                <div class="text-muted-foreground whitespace-pre-wrap leading-relaxed">{{ $event->description }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

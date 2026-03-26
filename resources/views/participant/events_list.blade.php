@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="text-center space-y-2">
        <h1 class="text-4xl font-bold font-display">Événements disponibles</h1>
        <p class="text-muted-foreground">Découvrez et inscrivez-vous aux meilleurs événements du moment.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($events as $event)
            <div class="bg-card border border-border rounded-2xl overflow-hidden flex flex-col shadow-sm hover:shadow-md transition-shadow">
                @if($event->image_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($event->image_path) }}" 
                         class="w-full h-48 object-cover" alt="{{ $event->title }}">
                @else
                    <div class="w-full h-48 bg-muted flex items-center justify-center">
                        <span class="text-muted-foreground">Pas d'image</span>
                    </div>
                @endif
                
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-primary bg-primary/10 px-2 py-1 rounded">
                            {{ $event->isFree() ? 'Gratuit' : number_format($event->price, 0, ',', ' ') . ' FCFA' }}
                        </span>
                        <span class="text-xs text-muted-foreground italic">
                            {{ $event->availableSpots() }} places restantes
                        </span>
                    </div>
                    
                    <h2 class="text-xl font-bold mb-2 line-clamp-1">{{ $event->title }}</h2>
                    <p class="text-sm text-muted-foreground line-clamp-3 mb-4 flex-1">
                        {{ $event->description }}
                    </p>
                    
                    <div class="space-y-3 pt-4 border-t border-border mt-auto">
                        <div class="flex items-center text-sm text-muted-foreground">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $event->starts_at->format('d M Y à H:i') }}
                        </div>
                        <div class="flex items-center text-sm text-muted-foreground">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $event->location }}
                        </div>
                        
                        @auth
                            @if(auth()->user()->registrations->contains('event_id', $event->id))
                                <button disabled class="w-full bg-muted text-muted-foreground py-2.5 rounded-xl font-medium cursor-not-allowed">
                                    Déjà inscrit
                                </button>
                            @elseif($event->isFull())
                                <button disabled class="w-full bg-destructive/10 text-destructive py-2.5 rounded-xl font-medium cursor-not-allowed">
                                    Complet
                                </button>
                            @else
                                <form action="{{ route('participant.events.register', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary text-primary-foreground py-2.5 rounded-xl font-medium hover:opacity-90 active:scale-[0.98] transition-all">
                                        S'inscrire maintenant
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block text-center w-full bg-primary text-primary-foreground py-2.5 rounded-xl font-medium hover:opacity-90 transition-all">
                                Se connecter pour s'inscrire
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-muted-foreground text-lg">Aucun événement n'est disponible pour le moment.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

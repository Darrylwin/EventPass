@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold font-display">Mes Inscriptions</h1>
            <p class="text-muted-foreground mt-1">Retrouvez ici vos pass pour vos événements à venir et passés.</p>
        </div>
        <a href="{{ route('participant.events.index') }}" class="inline-flex items-center text-sm font-medium text-primary hover:underline">
            Parcourir d'autres événements →
        </a>
    </div>

    @if($upcoming->isNotEmpty())
        <section class="space-y-4">
            <h2 class="text-xl font-semibold flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                Événements à venir
            </h2>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach($upcoming as $registration)
                    <div class="bg-card border border-border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-5 flex gap-4">
                            @if($registration->event->image_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($registration->event->image_path) }}" 
                                     class="w-24 h-24 object-cover rounded-lg flex-shrink-0" alt="">
                            @else
                                <div class="w-24 h-24 bg-muted rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-muted-foreground text-xs text-center px-1">Pas d'image</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-lg truncate">{{ $registration->event->title }}</h3>
                                <p class="text-sm text-muted-foreground">{{ $registration->event->starts_at->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-muted-foreground truncate">{{ $registration->event->location }}</p>
                                
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="bg-muted px-3 py-1.5 rounded-md border border-border">
                                        <span class="text-xs text-muted-foreground block uppercase font-bold tracking-tighter leading-none">Votre Pass</span>
                                        <span class="font-mono text-primary font-bold tracking-widest text-lg">{{ $registration->pass_code }}</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $registration->status === 'validé' ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground' }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if($past->isNotEmpty())
        <section class="space-y-4 pt-4">
            <h2 class="text-xl font-semibold text-muted-foreground">Historique des événements</h2>
            <div class="grid gap-4 md:grid-cols-2 opacity-75 grayscale-[0.5]">
                @foreach($past as $registration)
                    <div class="bg-card border border-border rounded-xl p-5 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="font-semibold">{{ $registration->event->title }}</h3>
                            <p class="text-xs text-muted-foreground">{{ $registration->event->starts_at->format('d/m/Y') }} • Finalisé</p>
                        </div>
                        <div class="text-right">
                             <div class="text-xs text-muted-foreground font-mono">{{ $registration->pass_code }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if($registrations->isEmpty())
        <div class="bg-card border border-dashed border-border rounded-2xl py-20 text-center">
            <div class="max-w-xs mx-auto space-y-4">
                <div class="w-16 h-16 bg-muted rounded-full flex items-center justify-center mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold">Aucune inscription</h2>
                    <p class="text-muted-foreground text-sm">Vous n'avez pas encore de pass. Pourquoi ne pas découvrir de nouveaux événements ?</p>
                </div>
                <a href="{{ route('participant.events.index') }}" class="inline-block bg-primary text-primary-foreground px-6 py-2 rounded-xl font-medium shadow-lg shadow-primary/20 hover:scale-105 transition">
                    Explorer les événements
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

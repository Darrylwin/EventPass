@extends('layouts.app')

@section('title', $event->title . ' — Inscrits')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Fil d'Ariane + actions --}}
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                    <a href="{{ route('organisateur.events.index') }}" class="hover:text-foreground transition-colors">
                        Mes événements
                    </a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-foreground truncate max-w-xs">{{ $event->title }}</span>
                </nav>
                <h1 class="text-2xl font-bold font-display">{{ $event->title }}</h1>
                <p class="text-sm text-muted-foreground mt-1">
                    {{ $event->starts_at->format('d M Y à H:i') }}
                    &bull;
                    {{ $event->location }}
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('organisateur.events.edit', $event) }}"
                   class="text-sm px-4 py-2 rounded-lg border border-border hover:bg-muted transition-colors">
                    Modifier
                </a>
            </div>
        </div>

        {{-- Méta-infos de l'événement --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $validatedCount = $event->registrations->where('status', 'validé')->count();
                $fillPct = $event->capacity > 0
                    ? min(100, round($validatedCount / $event->capacity * 100))
                    : 0;
            @endphp
            <div class="bg-card border border-border rounded-xl p-4 space-y-1">
                <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Inscrits validés</p>
                <p class="text-2xl font-bold text-primary">{{ $validatedCount }}</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4 space-y-1">
                <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Capacité totale</p>
                <p class="text-2xl font-bold">{{ $event->capacity }}</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4 space-y-1">
                <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Places restantes</p>
                <p class="text-2xl font-bold {{ $event->availableSpots() === 0 ? 'text-destructive' : '' }}">
                    {{ $event->availableSpots() }}
                </p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4 space-y-1">
                <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Remplissage</p>
                <p class="text-2xl font-bold">{{ $fillPct }}%</p>
                <div class="h-1.5 bg-muted rounded-full overflow-hidden mt-2">
                    <div
                        class="h-full rounded-full {{ $fillPct >= 100 ? 'bg-destructive' : ($fillPct >= 80 ? 'bg-amber-500' : 'bg-primary') }}"
                        style="width: {{ $fillPct }}%"></div>
                </div>
            </div>
        </div>

        {{-- Liste des inscrits --}}
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-border flex items-center justify-between">
                <h2 class="font-semibold text-base">
                    Inscrits
                    <span
                        class="text-muted-foreground font-normal text-sm">({{ $event->registrations->count() }})</span>
                </h2>
            </div>

            @if($event->registrations->isEmpty())
                <div class="py-16 text-center">
                    <p class="text-sm text-muted-foreground">Aucune inscription pour le moment.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-muted/50 text-xs text-muted-foreground uppercase tracking-wider">
                        <tr>
                            <th class="text-left px-5 py-3 font-medium">Participant</th>
                            <th class="text-left px-4 py-3 font-medium">Pass</th>
                            <th class="text-left px-4 py-3 font-medium">Statut</th>
                            <th class="text-left px-4 py-3 font-medium">Inscrit le</th>
                            <th class="text-right px-5 py-3 font-medium">Action</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                        @foreach($event->registrations as $registration)
                            <tr class="hover:bg-muted/30 transition-colors {{ $registration->status === 'annulé' ? 'opacity-60' : '' }}">
                                <td class="px-5 py-3">
                                    <p class="font-medium text-foreground">{{ $registration->user->name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $registration->user->email }}</p>
                                </td>
                                <td class="px-4 py-3">
                                <span
                                    class="font-mono text-sm font-medium {{ $registration->status === 'annulé' ? 'line-through text-muted-foreground' : 'text-primary' }}">
                                    {{ $registration->pass_code }}
                                </span>
                                </td>
                                <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $registration->status === 'validé' ? 'bg-primary/10 text-primary' : 'bg-destructive/10 text-destructive' }}">
                                    {{ ucfirst($registration->status) }}
                                </span>
                                </td>
                                <td class="px-4 py-3 text-muted-foreground text-xs whitespace-nowrap">
                                    {{ $registration->registered_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex justify-end">
                                        @if($registration->status === 'validé')
                                            <form method="POST"
                                                  action="{{ route('organisateur.registrations.invalidate', $registration) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-xs px-3 py-1.5 rounded-md border border-destructive/40 text-destructive hover:bg-destructive/10 transition-colors">
                                                    Invalider
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                  action="{{ route('organisateur.registrations.reactivate', $registration) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-xs px-3 py-1.5 rounded-md border border-primary/40 text-primary hover:bg-primary/10 transition-colors">
                                                    Réactiver
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
@endsection

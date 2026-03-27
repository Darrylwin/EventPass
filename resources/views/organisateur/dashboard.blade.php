@extends('layouts.app')

@section('title', 'Dashboard — ' . config('app.name'))

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        {{-- En-tête --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold font-display">Bonjour, {{ auth()->user()->name }}</h1>
                <p class="text-sm text-muted-foreground mt-0.5">Voici un aperçu de vos événements.</p>
            </div>
            <a href="{{ route('organisateur.events.create') }}"
               class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer un événement
            </a>
        </div>

        {{-- Statistiques --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-xl p-4 space-y-1">
                <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Total</p>
                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                <p class="text-xs text-muted-foreground">événements créés</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4 space-y-1">
                <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">À venir</p>
                <p class="text-2xl font-bold text-primary">{{ $stats['upcoming'] }}</p>
                <p class="text-xs text-muted-foreground">événements programmés</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4 space-y-1">
                <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Passés</p>
                <p class="text-2xl font-bold">{{ $stats['past'] }}</p>
                <p class="text-xs text-muted-foreground">événements terminés</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4 space-y-1">
                <p class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Complets</p>
                <p class="text-2xl font-bold {{ $stats['full'] > 0 ? 'text-amber-600' : '' }}">{{ $stats['full'] }}</p>
                <p class="text-xs text-muted-foreground">sans places dispo</p>
            </div>
        </div>

        {{-- Prochains événements --}}
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-border flex items-center justify-between">
                <h2 class="font-semibold text-base">Prochains événements</h2>
                <a href="{{ route('organisateur.events.index') }}"
                   class="text-xs text-primary hover:underline font-medium">
                    Voir tous →
                </a>
            </div>

            @if($events->isEmpty())
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-muted-foreground">Aucun événement créé pour l'instant.</p>
                    <a href="{{ route('organisateur.events.create') }}"
                       class="inline-flex items-center gap-1.5 mt-3 text-sm text-primary hover:underline font-medium">
                        Créer votre premier événement
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-muted/50 text-xs text-muted-foreground uppercase tracking-wider">
                        <tr>
                            <th class="text-left px-5 py-3 font-medium">Événement</th>
                            <th class="text-left px-4 py-3 font-medium">Date</th>
                            <th class="text-left px-4 py-3 font-medium">Remplissage</th>
                            <th class="text-left px-4 py-3 font-medium">Statut</th>
                            <th class="text-right px-5 py-3 font-medium">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                        @foreach($events as $event)
                            @php
                                $fillPct = $event->capacity > 0
                                    ? min(100, round($event->validated_registrations_count / $event->capacity * 100))
                                    : 0;
                            @endphp
                            <tr class="hover:bg-muted/30 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($event->image_path)
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::url($event->image_path) }}"
                                                class="w-10 h-10 object-cover rounded-lg border border-border shrink-0"
                                                alt="">
                                        @else
                                            <div
                                                class="w-10 h-10 bg-muted rounded-lg border border-border shrink-0"></div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-foreground line-clamp-1">{{ $event->title }}</p>
                                            <p class="text-xs text-muted-foreground">{{ $event->location }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-muted-foreground whitespace-nowrap">
                                    {{ $event->starts_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2 min-w-24">
                                        <div class="flex-1 h-1.5 bg-muted rounded-full overflow-hidden">
                                            <div class="h-full bg-primary rounded-full"
                                                 style="width: {{ $fillPct }}%"></div>
                                        </div>
                                        <span class="text-xs text-muted-foreground whitespace-nowrap">
                                        {{ $event->validated_registrations_count }}/{{ $event->capacity }}
                                    </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide
                                    {{ $event->status === 'publié' ? 'bg-primary/10 text-primary' : '' }}
                                    {{ $event->status === 'brouillon' ? 'bg-muted text-muted-foreground' : '' }}
                                    {{ $event->status === 'annulé' ? 'bg-destructive/10 text-destructive' : '' }}
                                    {{ $event->status === 'terminé' ? 'bg-muted text-muted-foreground' : '' }}
                                ">
                                    {{ ucfirst($event->status) }}
                                </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('organisateur.events.show', $event) }}"
                                           class="text-xs px-3 py-1.5 rounded-md border border-border hover:bg-muted transition-colors whitespace-nowrap">
                                            Inscrits
                                        </a>
                                        <a href="{{ route('organisateur.events.edit', $event) }}"
                                           class="text-xs px-3 py-1.5 rounded-md border border-border hover:bg-muted transition-colors">
                                            Modifier
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Inscriptions récentes --}}
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-border">
                <h2 class="font-semibold text-base">Inscriptions récentes</h2>
            </div>

            @if($registrations->isEmpty())
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-muted-foreground">Aucune inscription pour le moment.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-muted/50 text-xs text-muted-foreground uppercase tracking-wider">
                        <tr>
                            <th class="text-left px-5 py-3 font-medium">Participant</th>
                            <th class="text-left px-4 py-3 font-medium">Événement</th>
                            <th class="text-left px-4 py-3 font-medium">Pass</th>
                            <th class="text-left px-4 py-3 font-medium">Statut</th>
                            <th class="text-right px-5 py-3 font-medium">Action</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                        @foreach($registrations as $registration)
                            <tr class="hover:bg-muted/30 transition-colors">
                                <td class="px-5 py-3">
                                    <p class="font-medium text-foreground">{{ $registration->user->name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $registration->user->email }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="line-clamp-1 text-muted-foreground">{{ $registration->event->title }}</p>
                                </td>
                                <td class="px-4 py-3 font-mono text-sm font-medium">{{ $registration->pass_code }}</td>
                                <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $registration->status === 'validé' ? 'bg-primary/10 text-primary' : 'bg-destructive/10 text-destructive' }}">
                                    {{ ucfirst($registration->status) }}
                                </span>
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

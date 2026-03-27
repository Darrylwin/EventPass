@extends('layouts.app')

@section('title', 'Mes événements — ' . config('app.name'))

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        {{-- En-tête --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold font-display">Mes événements</h1>
                <p class="text-sm text-muted-foreground mt-0.5">Gérez vos événements, leurs inscriptions et statuts.</p>
            </div>
            <a href="{{ route('organisateur.events.create') }}"
               class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvel événement
            </a>
        </div>

        {{-- Filtres --}}
        <div class="bg-card border border-border rounded-xl p-4">
            <form method="GET" action="{{ route('organisateur.events.index') }}"
                  class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-32">
                    <label for="status" class="block text-xs font-medium text-muted-foreground mb-1.5">Statut</label>
                    <select name="status" id="status"
                            class="w-full bg-input/30 border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="">Tous les statuts</option>
                        @foreach(['brouillon', 'publié', 'annulé', 'terminé'] as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-32">
                    <label for="starts_from" class="block text-xs font-medium text-muted-foreground mb-1.5">Du</label>
                    <input type="date" id="starts_from" name="starts_from"
                           value="{{ $filters['starts_from'] ?? '' }}"
                           class="w-full bg-input/30 border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>

                <div class="flex-1 min-w-32">
                    <label for="starts_to" class="block text-xs font-medium text-muted-foreground mb-1.5">Au</label>
                    <input type="date" id="starts_to" name="starts_to"
                           value="{{ $filters['starts_to'] ?? '' }}"
                           class="w-full bg-input/30 border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>

                <div class="flex gap-2 shrink-0">
                    <button type="submit"
                            class="bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                        Filtrer
                    </button>
                    @if(request()->hasAny(['status', 'starts_from', 'starts_to']))
                        <a href="{{ route('organisateur.events.index') }}"
                           class="px-4 py-2 rounded-lg text-sm border border-border hover:bg-muted transition-colors">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            @if($events->isEmpty())
                <div class="py-16 text-center px-4">
                    <div class="w-12 h-12 bg-muted rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-muted-foreground" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-foreground">Aucun événement trouvé</p>
                    <p class="text-xs text-muted-foreground mt-1">
                        @if(request()->hasAny(['status', 'starts_from', 'starts_to']))
                            Essayez de modifier vos filtres.
                        @else
                            Commencez par créer votre premier événement.
                        @endif
                    </p>
                    @if(!request()->hasAny(['status', 'starts_from', 'starts_to']))
                        <a href="{{ route('organisateur.events.create') }}"
                           class="inline-flex items-center gap-1.5 mt-4 text-sm text-primary hover:underline font-medium">
                            Créer un événement
                        </a>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-muted/50 text-xs text-muted-foreground uppercase tracking-wider">
                        <tr>
                            <th class="text-left px-5 py-3 font-medium">Événement</th>
                            <th class="text-left px-4 py-3 font-medium">Date</th>
                            <th class="text-left px-4 py-3 font-medium">Tarif</th>
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
                                                class="w-10 h-10 bg-muted rounded-lg border border-border shrink-0 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-muted-foreground" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="1.5"
                                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-medium text-foreground line-clamp-1">{{ $event->title }}</p>
                                            <p class="text-xs text-muted-foreground truncate">{{ $event->location }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-muted-foreground whitespace-nowrap text-xs">
                                    {{ $event->starts_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-muted-foreground">
                                    {{ (float) $event->price > 0 ? number_format((float) $event->price, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2 min-w-28">
                                        <div class="flex-1 h-1.5 bg-muted rounded-full overflow-hidden">
                                            <div
                                                class="h-full rounded-full {{ $fillPct >= 100 ? 'bg-destructive' : ($fillPct >= 80 ? 'bg-amber-500' : 'bg-primary') }}"
                                                style="width: {{ $fillPct }}%">
                                            </div>
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
                                        <form method="POST" action="{{ route('organisateur.events.destroy', $event) }}"
                                              onsubmit="return confirm('Supprimer définitivement cet événement ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-xs px-3 py-1.5 rounded-md border border-destructive/40 text-destructive hover:bg-destructive/10 transition-colors">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($events->hasPages())
                    <div class="px-5 py-4 border-t border-border">
                        {{ $events->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
@endsection

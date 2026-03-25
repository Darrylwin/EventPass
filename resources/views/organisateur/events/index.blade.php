@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold font-display">Mes événements</h1>
                <p class="text-sm text-muted-foreground mt-1">Gérez vos événements, leurs inscriptions et leurs statuts.</p>
            </div>

            <a
                href="{{ route('organisateur.events.create') }}"
                class="inline-flex items-center justify-center bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition"
            >
                Nouvel événement
            </a>
        </div>

        <div class="bg-card border border-border rounded-xl p-4">
            <form method="GET" action="{{ route('organisateur.events.index') }}" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                <div>
                    <label for="status" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Statut</label>
                    <select name="status" id="status" class="w-full bg-input/30 border border-border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="">Tous</option>
                        @foreach(['brouillon', 'publié', 'annulé', 'terminé'] as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="starts_from" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Date début (de)</label>
                    <input type="date" id="starts_from" name="starts_from" value="{{ $filters['starts_from'] ?? '' }}" class="w-full bg-input/30 border border-border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>

                <div>
                    <label for="starts_to" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Date début (à)</label>
                    <input type="date" id="starts_to" name="starts_to" value="{{ $filters['starts_to'] ?? '' }}" class="w-full bg-input/30 border border-border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-primary text-primary-foreground px-4 py-2.5 rounded-lg text-sm font-medium hover:opacity-90 transition">Filtrer</button>
                    <a href="{{ route('organisateur.events.index') }}" class="px-4 py-2.5 rounded-lg text-sm border border-border hover:bg-muted transition">Réinitialiser</a>
                </div>
            </form>
        </div>

        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-muted/60 text-muted-foreground uppercase tracking-wider text-xs">
                    <tr>
                        <th class="text-left px-4 py-3">Titre</th>
                        <th class="text-left px-4 py-3">Date</th>
                        <th class="text-left px-4 py-3">Lieu</th>
                        <th class="text-left px-4 py-3">Capacité</th>
                        <th class="text-left px-4 py-3">Tarif</th>
                        <th class="text-left px-4 py-3">Statut</th>
                        <th class="text-right px-4 py-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($events as $event)
                        <tr class="border-t border-border">
                            <td class="px-4 py-3">
                                <p class="font-medium text-foreground">{{ $event->title }}</p>
                                <p class="text-xs text-muted-foreground">{{ $event->validated_registrations_count }} inscrits validés</p>
                            </td>
                            <td class="px-4 py-3">{{ $event->starts_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $event->location }}</td>
                            <td class="px-4 py-3">
                                {{ $event->validated_registrations_count }}/{{ $event->capacity }}
                            </td>
                            <td class="px-4 py-3">
                                {{ (float) $event->price > 0 ? number_format((float) $event->price, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full bg-muted text-foreground text-xs">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('organisateur.events.show', $event) }}" class="text-xs px-3 py-1.5 rounded-md border border-border hover:bg-muted transition">Inscrits</a>
                                    <a href="{{ route('organisateur.events.edit', $event) }}" class="text-xs px-3 py-1.5 rounded-md border border-border hover:bg-muted transition">Modifier</a>
                                    <form method="POST" action="{{ route('organisateur.events.destroy', $event) }}" onsubmit="return confirm('Supprimer cet événement ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs px-3 py-1.5 rounded-md border border-destructive/40 text-destructive hover:bg-destructive/10 transition">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-muted-foreground">
                                Aucun événement pour le moment.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-border">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection

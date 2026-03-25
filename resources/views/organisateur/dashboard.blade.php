@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold font-display">Dashboard Organisateur</h1>
                <p class="text-sm text-muted-foreground mt-1">Bienvenue {{ auth()->user()->name }}, voici un aperçu rapide de vos événements.</p>
            </div>

            <a href="{{ route('organisateur.events.create') }}" class="inline-flex items-center justify-center bg-primary text-primary-foreground px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                Créer un événement
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-muted-foreground">Total</p>
                <p class="text-2xl font-semibold mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-muted-foreground">À venir</p>
                <p class="text-2xl font-semibold mt-1">{{ $stats['upcoming'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-muted-foreground">Passés</p>
                <p class="text-2xl font-semibold mt-1">{{ $stats['past'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4">
                <p class="text-xs uppercase tracking-wider text-muted-foreground">Complets</p>
                <p class="text-2xl font-semibold mt-1">{{ $stats['full'] }}</p>
            </div>
        </div>

        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-border flex items-center justify-between">
                <h2 class="text-lg font-semibold">Prochains événements</h2>
                <a href="{{ route('organisateur.events.index') }}" class="text-sm text-primary hover:underline">Voir tout</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-muted/60 text-muted-foreground uppercase tracking-wider text-xs">
                    <tr>
                        <th class="text-left px-4 py-3">Titre</th>
                        <th class="text-left px-4 py-3">Date</th>
                        <th class="text-left px-4 py-3">Inscriptions</th>
                        <th class="text-right px-4 py-3">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($events as $event)
                        <tr class="border-t border-border">
                            <td class="px-4 py-3">{{ $event->title }}</td>
                            <td class="px-4 py-3">{{ $event->starts_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $event->validated_registrations_count }}/{{ $event->capacity }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('organisateur.events.show', $event) }}" class="text-xs px-3 py-1.5 rounded-md border border-border hover:bg-muted transition">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">Aucun événement pour le moment.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

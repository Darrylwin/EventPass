@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold font-display">{{ $event->title }}</h1>
                <p class="text-sm text-muted-foreground mt-1">
                    {{ $event->starts_at->format('d/m/Y H:i') }} • {{ $event->location }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('organisateur.events.edit', $event) }}" class="text-sm px-4 py-2 rounded-lg border border-border hover:bg-muted transition">Modifier</a>
                <a href="{{ route('organisateur.events.index') }}" class="text-sm px-4 py-2 rounded-lg border border-border hover:bg-muted transition">Tous les événements</a>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-card border border-border rounded-xl p-5">
                <h2 class="text-lg font-semibold mb-3">Description</h2>
                <p class="text-sm text-muted-foreground leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
            </div>

            <div class="bg-card border border-border rounded-xl p-5 space-y-3">
                <h2 class="text-lg font-semibold">Informations</h2>
                <p class="text-sm text-muted-foreground"><span class="text-foreground font-medium">Capacité:</span> {{ $event->capacity }}</p>
                <p class="text-sm text-muted-foreground"><span class="text-foreground font-medium">Tarif:</span> {{ (float) $event->price > 0 ? number_format((float) $event->price, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}</p>
                <p class="text-sm text-muted-foreground"><span class="text-foreground font-medium">Statut:</span> {{ ucfirst($event->status) }}</p>
                @if($event->image_path)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($event->image_path) }}"
                        alt="Image de {{ $event->title }}"
                        class="w-full h-40 object-cover rounded-lg border border-border"
                    >
                @endif
            </div>
        </div>

        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="px-4 py-3 border-b border-border">
                <h2 class="text-lg font-semibold">Inscrits ({{ $event->registrations->count() }})</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-muted/60 text-muted-foreground uppercase tracking-wider text-xs">
                    <tr>
                        <th class="text-left px-4 py-3">Participant</th>
                        <th class="text-left px-4 py-3">Pass</th>
                        <th class="text-left px-4 py-3">Statut</th>
                        <th class="text-left px-4 py-3">Inscrit le</th>
                        <th class="text-right px-4 py-3">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($event->registrations as $registration)
                        <tr class="border-t border-border">
                            <td class="px-4 py-3">
                                <p class="font-medium text-foreground">{{ $registration->user->name }}</p>
                                <p class="text-xs text-muted-foreground">{{ $registration->user->email }}</p>
                            </td>
                            <td class="px-4 py-3 font-mono">{{ $registration->pass_code }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs {{ $registration->status === 'validé' ? 'bg-primary/15 text-primary' : 'bg-destructive/10 text-destructive' }}">
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $registration->registered_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end">
                                    @if($registration->status === 'validé')
                                        <form method="POST" action="{{ route('organisateur.registrations.invalidate', $registration) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs px-3 py-1.5 rounded-md border border-destructive/40 text-destructive hover:bg-destructive/10 transition">Invalider</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('organisateur.registrations.reactivate', $registration) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs px-3 py-1.5 rounded-md border border-primary/40 text-primary hover:bg-primary/10 transition">Réactiver</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-muted-foreground">Aucune inscription pour cet événement.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

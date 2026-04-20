{{--
    Partial : carte d'événement
    Variables attendues :
    - $event  : App\Models\Event
    - $isPast : bool
--}}
<a href="{{ route('events.show', $event) }}"
   class="group flex flex-col bg-card border border-border rounded-2xl overflow-hidden transition-all
          {{ $isPast
              ? 'opacity-60 grayscale hover:opacity-90 hover:grayscale-0'
              : 'hover:-translate-y-0.5 hover:shadow-md' }}">

    {{-- Image + badges --}}
    <div class="relative aspect-video overflow-hidden bg-muted">
        @if($event->image_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($event->image_path) }}"
                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                 alt="{{ $event->title }}">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-10 h-10 text-muted-foreground/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        {{-- Badges en overlay --}}
        <div class="absolute top-3 left-3 flex gap-2">
            {{-- Tarif --}}
            <span
                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-background/90 backdrop-blur-sm text-foreground border border-border/50 shadow-sm">
                {{ $event->isFree() ? 'Gratuit' : number_format((float)$event->price, 0, ',', ' ') . ' FCFA' }}
            </span>
        </div>

        @if($isPast)
            {{-- Badge "Terminé" bien visible pour les passés --}}
            <div class="absolute top-3 right-3">
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-foreground/80 text-background backdrop-blur-sm">
                    Terminé
                </span>
            </div>
        @elseif($event->isFull())
            <div class="absolute top-3 right-3">
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-destructive text-destructive-foreground shadow-sm">
                    Complet
                </span>
            </div>
        @elseif($event->availableSpots() <= 5 && $event->availableSpots() > 0)
            <div class="absolute top-3 right-3">
                <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/90 text-white backdrop-blur-sm animate-pulse">
                    {{ $event->availableSpots() }} restante{{ $event->availableSpots() > 1 ? 's' : '' }}
                </span>
            </div>
        @endif
    </div>

    {{-- Contenu --}}
    <div class="flex flex-col flex-1 p-5">
        <h2 class="font-semibold text-base line-clamp-1 group-hover:text-primary transition-colors">
            {{ $event->title }}
        </h2>

        <p class="text-sm text-muted-foreground line-clamp-2 mt-1.5 flex-1">
            {{ $event->description }}
        </p>

        <div class="mt-4 pt-4 border-t border-border space-y-1.5">
            <div class="flex items-center gap-2 text-xs text-muted-foreground">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $event->starts_at->format('d M Y à H:i') }}
            </div>
            <div class="flex items-center gap-2 text-xs text-muted-foreground">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="truncate">{{ $event->location }}</span>
            </div>
        </div>

        {{-- CTA --}}
        <div class="mt-4">
            @if($isPast)
                <div class="w-full text-center py-2 rounded-xl text-xs font-medium text-muted-foreground bg-muted">
                    Événement terminé
                </div>
            @elseif($event->isFull())
                <div class="w-full text-center py-2 rounded-xl text-xs font-medium text-destructive bg-destructive/10">
                    Complet
                </div>
            @else
                <div
                    class="w-full text-center py-2 rounded-xl text-xs font-medium text-primary bg-primary/10 group-hover:bg-primary group-hover:text-primary-foreground transition-colors">
                    Voir et s'inscrire
                </div>
            @endif
        </div>
    </div>
</a>

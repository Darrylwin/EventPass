@if ($errors->any())
    <div class="bg-destructive/10 border border-destructive/30 text-destructive text-sm rounded-lg px-4 py-3">
        {{ $errors->first() }}
    </div>
@endif

<div>
    <label for="title" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Titre</label>
    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
</div>

<div>
    <label for="description" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Description</label>
    <textarea name="description" id="description" rows="5" required class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">{{ old('description', $event->description) }}</textarea>
</div>

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label for="starts_at" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Date et heure</label>
        <input
            type="datetime-local"
            name="starts_at"
            id="starts_at"
            value="{{ old('starts_at', $event->starts_at ? $event->starts_at->format('Y-m-d\\TH:i') : '') }}"
            required
            class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
        >
    </div>

    <div>
        <label for="location" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Lieu</label>
        <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" required class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
    </div>
</div>

<div class="grid sm:grid-cols-3 gap-4">
    <div>
        <label for="capacity" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Capacité</label>
        <input type="number" name="capacity" id="capacity" min="1" value="{{ old('capacity', $event->capacity) }}" required class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
    </div>

    <div>
        <label for="price" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Tarif (FCFA)</label>
        <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price', $event->price ?? 0) }}" required class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
    </div>

    <div>
        <label for="status" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Statut</label>
        <select name="status" id="status" required class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            @foreach(['brouillon', 'publié', 'annulé', 'terminé'] as $status)
                <option value="{{ $status }}" @selected(old('status', $event->status ?: 'brouillon') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="space-y-3">
    <div>
        <label for="image_path" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Image</label>
        <input type="file" name="image_path" id="image_path" accept="image/*" class="w-full text-sm text-muted-foreground">
    </div>

    @if($event->image_path)
        <div class="flex items-start gap-4">
            <img src="{{ \Illuminate\Support\Facades\Storage::url($event->image_path) }}" alt="Image actuelle" class="w-36 h-24 object-cover rounded-lg border border-border">
            <label class="inline-flex items-center gap-2 text-sm text-muted-foreground mt-1">
                <input type="checkbox" name="remove_image" value="1" class="w-4 h-4 accent-primary">
                Supprimer l'image actuelle
            </label>
        </div>
    @endif
</div>

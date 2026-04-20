@if ($errors->any())
    <div class="bg-destructive/10 border border-destructive/30 text-destructive text-sm rounded-lg px-4 py-3">
        {{ $errors->first() }}
    </div>
@endif

<div>
    <label for="title"
           class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Titre</label>
    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
           class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
</div>

<div>
    <label for="description" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Description</label>
    <textarea name="description" id="description" rows="5" required
              class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">{{ old('description', $event->description) }}</textarea>
</div>

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label for="starts_date" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Date
            et heure</label>

        {{-- Sélecteur enrichi (Flatpickr) + champs fallback date/time --}}
        <input
            type="text"
            id="starts_at_picker"
            name="starts_at_picker"
            value="{{ old('starts_at', $event->starts_at ? $event->starts_at->format('d/m/Y H:i') : '') }}"
            placeholder="Sélectionnez la date et l'heure"
            class="w-full mb-2 bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
        >

        <div class="flex gap-2">
            <input
                type="date"
                name="starts_date"
                id="starts_date"
                value="{{ old('starts_date', $event->starts_at ? $event->starts_at->format('Y-m-d') : '') }}"
                required
                class="w-1/2 bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
            >

            <input
                type="time"
                name="starts_time"
                id="starts_time"
                value="{{ old('starts_time', $event->starts_at ? $event->starts_at->format('H:i') : '') }}"
                required
                class="w-1/2 bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
            >
        </div>

        {{-- Valeur finale envoyée au serveur (synchronisée par JS) --}}
        <input type="hidden" name="starts_at" id="starts_at" value="{{ old('starts_at', $event->starts_at ? $event->starts_at->format('Y-m-d\\TH:i') : '') }}">
    </div>

    <div>
        <label for="location" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Lieu</label>
        <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" required
               class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
    </div>
</div>

<div class="grid sm:grid-cols-3 gap-4">
    <div>
        <label for="capacity" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Capacité</label>
        <input type="number" name="capacity" id="capacity" min="1" value="{{ old('capacity', $event->capacity) }}"
               required
               class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
    </div>

    <div>
        <label for="price" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Tarif
            (FCFA)</label>
        <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price', $event->price ?? 0) }}"
               required
               class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
    </div>

    <div>
        <label for="status" class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Statut</label>
        <select name="status" id="status" required
                class="w-full bg-input/30 border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            @foreach(['brouillon', 'publié', 'annulé', 'terminé'] as $status)
                <option value="{{ $status }}" @selected(old('status', $event->status ?: 'brouillon') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

{{-- Bloc image avec preview dynamique --}}
<div
    x-data="imageUpload('{{ $event->image_path ? \Illuminate\Support\Facades\Storage::url($event->image_path) : '' }}')"
    class="space-y-3">
    <label class="block text-xs font-medium text-muted-foreground uppercase tracking-widest mb-2">Image</label>

    {{-- Zone de drop / preview --}}
    <div
        class="relative border-2 border-dashed border-border rounded-xl overflow-hidden transition-colors"
        :class="preview ? 'border-primary/40' : 'border-border hover:border-primary/40'"
    >
        {{-- Preview de l'image --}}
        <template x-if="preview">
            <div class="relative">
                <img :src="preview" alt="Preview" class="w-full h-56 object-cover">
                <div
                    class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                    <label
                        for="image_path"
                        class="cursor-pointer bg-white/20 backdrop-blur-sm text-white text-xs font-medium px-4 py-2 rounded-lg border border-white/30 hover:bg-white/30 transition"
                    >
                        Changer
                    </label>
                    <button
                        type="button"
                        @click="removeImage()"
                        class="bg-destructive/80 backdrop-blur-sm text-white text-xs font-medium px-4 py-2 rounded-lg border border-destructive/50 hover:bg-destructive transition"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </template>

        {{-- Zone vide (drag & drop visuel) --}}
        <template x-if="!preview">
            <label
                for="image_path"
                class="flex flex-col items-center justify-center h-56 cursor-pointer text-muted-foreground hover:text-foreground transition-colors px-4"
            >
                <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-medium">Cliquez pour choisir une image</span>
                <span class="text-xs mt-1 opacity-60">PNG, JPG, WEBP — max 2 Mo</span>
            </label>
        </template>
    </div>

    {{-- Input file caché --}}
    <input
        type="file"
        id="image_path"
        name="image_path"
        accept="image/*"
        class="sr-only"
        @change="onFileChange($event)"
    >

    {{-- Champ caché pour signaler la suppression de l'image existante --}}
    <input type="hidden" name="remove_image" :value="removeFlag ? '1' : '0'">

    {{-- Indication fichier sélectionné --}}
    <p x-show="fileName" x-text="'Fichier sélectionné : ' + fileName" class="text-xs text-muted-foreground"></p>
</div>

<script>
    function imageUpload(initialPreview) {
        return {
            preview: initialPreview || null,
            fileName: '',
            removeFlag: false,

            onFileChange(event) {
                const file = event.target.files[0];
                if (!file) return;

                this.fileName = file.name;
                this.removeFlag = false;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            removeImage() {
                this.preview = null;
                this.fileName = '';
                this.removeFlag = true;

                // Réinitialise l'input file pour permettre de re-sélectionner le même fichier
                const input = document.getElementById('image_path');
                input.value = '';
            },
        };
    }
</script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateEl = document.getElementById('starts_date');
        const timeEl = document.getElementById('starts_time');
        const hiddenEl = document.getElementById('starts_at');
        const pickerEl = document.getElementById('starts_at_picker');

        function syncStartsAt() {
            if (!dateEl || !hiddenEl) return;
            const date = dateEl.value;
            const time = timeEl ? timeEl.value : '';

            if (!date) {
                hiddenEl.value = '';
                return;
            }

            // Format attendu pour input datetime-local / validation côté serveur
            hiddenEl.value = time ? `${date}T${time}` : date;

            // Keep picker display in sync for clarity
            if (pickerEl && typeof flatpickr === 'undefined') {
                // If flatpickr not available, just set the text value
                pickerEl.value = time ? `${date.split('-').reverse().join('/')} ${time}` : date.split('-').reverse().join('/');
            }
        }

        if (dateEl) dateEl.addEventListener('change', syncStartsAt);
        if (timeEl) timeEl.addEventListener('change', syncStartsAt);

        const form = dateEl ? dateEl.closest('form') : null;
        if (form) form.addEventListener('submit', syncStartsAt);

        // Initial sync (pré-remplissage si edition)
        syncStartsAt();

        // Load Flatpickr if available and initialize
        function initFlatpickr() {
            if (typeof flatpickr === 'undefined' || !pickerEl) return;

            // French locale if available
            const frLocale = (flatpickr && flatpickr.l10ns && flatpickr.l10ns.fr) ? flatpickr.l10ns.fr : null;

            flatpickr(pickerEl, {
                enableTime: true,
                dateFormat: "Y-m-d\TH:i",
                altInput: true,
                altFormat: "d/m/Y H:i",
                time_24hr: true,
                minuteIncrement: 5,
                locale: frLocale || undefined,
                defaultDate: hiddenEl && hiddenEl.value ? hiddenEl.value : null,
                onChange: function (selectedDates, dateStr) {
                    if (!hiddenEl) return;
                    hiddenEl.value = dateStr;

                    if (selectedDates.length) {
                        const dt = selectedDates[0];
                        const y = dt.getFullYear();
                        const m = String(dt.getMonth() + 1).padStart(2, '0');
                        const d = String(dt.getDate()).padStart(2, '0');
                        const hh = String(dt.getHours()).padStart(2, '0');
                        const mm = String(dt.getMinutes()).padStart(2, '0');

                        if (dateEl) dateEl.value = `${y}-${m}-${d}`;
                        if (timeEl) timeEl.value = `${hh}:${mm}`;
                    }
                },
            });
        }

        // If flatpickr already loaded
        if (typeof flatpickr !== 'undefined') {
            initFlatpickr();
        } else {
            // Try to load flatpickr from CDN dynamically
            const cssHref = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css';
            const jsSrc = 'https://cdn.jsdelivr.net/npm/flatpickr';
            const frSrc = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js';

            // Insert CSS
            if (!document.querySelector(`link[href="${cssHref}"]`)) {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = cssHref;
                document.head.appendChild(link);
            }

            // Insert main script
            if (!document.querySelector(`script[src="${jsSrc}"]`)) {
                const s = document.createElement('script');
                s.src = jsSrc;
                s.onload = function () {
                    // load french locale then init
                    const sf = document.createElement('script');
                    sf.src = frSrc;
                    sf.onload = initFlatpickr;
                    document.head.appendChild(sf);
                };
                document.head.appendChild(s);
            } else {
                // already present but not loaded yet
                const existing = document.querySelector(`script[src="${jsSrc}"]`);
                existing.addEventListener('load', function () {
                    const sf = document.createElement('script');
                    sf.src = frSrc;
                    sf.onload = initFlatpickr;
                    document.head.appendChild(sf);
                });
            }
        }
    });
    </script>

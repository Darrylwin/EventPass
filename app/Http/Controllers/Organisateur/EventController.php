<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'total' => $user->events()->count(),
            'upcoming' => $user->events()->upcoming()->count(),
            'past' => $user->events()->past()->count(),
            'full' => $user->events()->full()->count(),
        ];

        $events = $user->events()
            ->withCount([
                'registrations as validated_registrations_count' => fn ($query) => $query->where('status', 'validé'),
            ])
            ->latest('starts_at')
            ->take(5)
            ->get();

        $registrations = Registration::query()
            ->whereHas('event', fn ($query) => $query->where('organizer_id', $user->id))
            ->with(['user', 'event'])
            ->latest('registered_at')
            ->take(10)
            ->get();

        return view('organisateur.dashboard', compact('stats', 'events', 'registrations'));
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['brouillon', 'publié', 'annulé', 'terminé'])],
            'starts_from' => ['nullable', 'date'],
            'starts_to' => ['nullable', 'date'],
        ]);

        $events = $request->user()->events()
            ->withCount([
                'registrations as validated_registrations_count' => fn ($query) => $query->where('status', 'validé'),
            ])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['starts_from'] ?? null, fn ($query, $date) => $query->whereDate('starts_at', '>=', $date))
            ->when($filters['starts_to'] ?? null, fn ($query, $date) => $query->whereDate('starts_at', '<=', $date))
            ->latest('starts_at')
            ->paginate(10)
            ->withQueryString();

        return view('organisateur.events.index', compact('events', 'filters'));
    }

    public function create(): View
    {
        $event = new Event();

        return view('organisateur.events.create', compact('event'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['organizer_id'] = $request->user()->id;

        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('events', 'public');
        }

        Event::create($data);

        return redirect()->route('organisateur.events.index')
            ->with('success', 'Événement créé avec succès.');
    }

    public function show(Event $event): View
    {
        $this->authorizeOrganizerOwnership($event);

        $event->load([
            'registrations' => fn ($query) => $query->with('user')->latest('registered_at'),
        ]);

        return view('organisateur.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $this->authorizeOrganizerOwnership($event);

        return view('organisateur.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeOrganizerOwnership($event);

        $data = $this->validatedData($request);

        if ($request->boolean('remove_image') && $event->image_path) {
            Storage::disk('public')->delete($event->image_path);
            $data['image_path'] = null;
        }

        if ($request->hasFile('image_path')) {
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }

            $data['image_path'] = $request->file('image_path')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('organisateur.events.index')
            ->with('success', 'Événement mis à jour avec succès.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeOrganizerOwnership($event);

        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return redirect()->route('organisateur.events.index')
            ->with('success', 'Événement supprimé avec succès.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'starts_at' => ['required', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'image_path' => ['nullable', 'image', 'max:2048'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['brouillon', 'publié', 'annulé', 'terminé'])],
            'remove_image' => ['nullable', 'boolean'],
        ]);
    }

    private function authorizeOrganizerOwnership(Event $event): void
    {
        abort_unless($event->organizer_id === Auth::id(), 403, 'Accès non autorisé à cet événement.');
    }
}

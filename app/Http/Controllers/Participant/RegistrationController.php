<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Liste publique des événements pour l'exploration (KOKODOKO temporary).
     */
    public function publicIndex(): View
    {
        $events = Event::upcoming()->where('status', 'publié')->latest('starts_at')->get();
        return view('participant.events_list', compact('events'));
    }

    /**
     * Affiche le dashboard du participant avec ses inscriptions.
     */
    public function index(): View
    {
        $user = Auth::user();

        $registrations = $user->registrations()
            ->with('event')
            ->latest('registered_at')
            ->get();

        // Séparation des inscriptions pour la vue (optionnel, on peut le faire en Blade aussi)
        $upcoming = $registrations->filter(fn($r) => $r->event->starts_at->isFuture());
        $past = $registrations->filter(fn($r) => $r->event->starts_at->isPast());

        return view('participant.dashboard', compact('upcoming', 'past', 'registrations'));
    }

    /**
     * Enregistre le participant à un événement.
     */
    public function store(Event $event): RedirectResponse
    {
        $user = Auth::user();

        // 1. Vérification de l'unicité (un participant = un pass par événement)
        $alreadyRegistered = Registration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->back()->with('error', 'Vous êtes déjà inscrit à cet événement.');
        }

        // 2. Vérification de la capacité côté serveur
        if ($event->isFull()) {
            return redirect()->back()->with('error', 'Désolé, cet événement est complet.');
        }

        // 3. Génération du pass_code unique
        // On s'assure qu'il est vraiment unique en base (boucle de sécurité)
        do {
            $passCode = Str::upper(Str::random(8));
        } while (Registration::where('pass_code', $passCode)->exists());

        // 4. Création de l'inscription
        Registration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'pass_code' => $passCode,
            'status' => 'validé',
            'registered_at' => now(),
        ]);

        return redirect()->route('participant.dashboard')
            ->with('success', 'Votre inscription a été enregistrée avec succès ! Votre pass est disponible ci-dessous.');
    }
}

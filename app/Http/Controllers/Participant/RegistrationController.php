<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationConfirmed;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Liste publique des événements pour l'exploration (KOKODOKO temporary).
     */
    public function publicIndex(): View
    {
        // Pagination + withCount pour éviter de charger tous les événements et prévenir les N+1
        $events = Event::where('status', 'publié')
            ->withCount(['registrations as validated_registrations_count' => fn ($query) => $query->where('status', 'validé')])
            ->orderBy('starts_at', 'desc')
            ->paginate(12);

        // On sépare les événements de la page courante en à venir / passés (pour l'affichage)
        $collection = $events->getCollection();
        $upcomingEvents = $collection->filter(fn ($e) => ! $e->starts_at->isPast());
        $pastEvents = $collection->filter(fn ($e) => $e->starts_at->isPast());

        return view('participant.events_list', compact('events', 'upcomingEvents', 'pastEvents'));
    }

    /**
     * Affiche les détails d'un événement (KOKODOKO temporary).
     */
    public function publicShow(Event $event): View
    {
        // On s'assure que l'événement est bien publié
        abort_if($event->status !== 'publié', 404);
        // Précharger l'éventuelle inscription de l'utilisateur connecté afin d'éviter le lazy-loading
        $userRegistration = null;
        if (Auth::check()) {
            $user = Auth::user();
            $userRegistration = $user->registrations()->where('event_id', $event->id)->first();
        }

        return view('participant.event_show', compact('event', 'userRegistration'));
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
        $upcoming = $registrations->filter(fn ($r) => $r->event->starts_at->isFuture());
        $past = $registrations->filter(fn ($r) => $r->event->starts_at->isPast());

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

        // 2. Création de l'inscription de façon atomique (éviter le surbooking)
        try {
            $registration = DB::transaction(function () use ($event, $user) {
                // Verrouille la ligne de l'événement pour éviter les courses
                $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->first();

                $taken = $lockedEvent->registrations()->where('status', 'validé')->count();
                $spots = max(0, $lockedEvent->capacity - $taken);

                if ($spots <= 0) {
                    // Plus de place au moment de la validation
                    throw new \RuntimeException('full');
                }

                // Génération du pass_code unique
                do {
                    $passCode = Str::upper(Str::random(8));
                } while (Registration::where('pass_code', $passCode)->exists());

                return Registration::create([
                    'event_id' => $lockedEvent->id,
                    'user_id' => $user->id,
                    'pass_code' => $passCode,
                    'status' => 'validé',
                    'registered_at' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'full') {
                // Comportement historique : message simple "complet"
                return redirect()->back()->with('error', 'Désolé, cet événement est complet.');
            }
            Log::error('Registration runtime error', ['exception' => $e]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
        } catch (\Exception $e) {
            Log::error('Registration error', ['exception' => $e]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
        }

        // 3. Envoi d'email de confirmation (best-effort)
        try {
            Mail::to($user->email)->send(new RegistrationConfirmed($registration));
        } catch (\Exception $e) {
            Log::warning('Failed to send registration email', ['exception' => $e, 'user_id' => $user->id]);
        }

        // 4. Redirection : comportement historique vers le tableau de bord participant
        return redirect()->route('participant.dashboard')
            ->with('success', 'Votre inscription a été enregistrée avec succès ! Votre pass est disponible ci-dessous.');
    }
}

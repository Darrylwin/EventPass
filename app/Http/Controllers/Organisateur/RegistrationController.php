<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function invalidate(Registration $registration): RedirectResponse
    {
        $this->authorizeOrganizerOwnership($registration);

        $registration->update(['status' => 'annulé']);

        return back()->with('success', 'Pass invalidé avec succès.');
    }

    public function reactivate(Registration $registration): RedirectResponse
    {
        $this->authorizeOrganizerOwnership($registration);

        $registration->update(['status' => 'validé']);

        return back()->with('success', 'Pass réactivé avec succès.');
    }

    private function authorizeOrganizerOwnership(Registration $registration): void
    {
        abort_unless(
            $registration->event()->where('organizer_id', Auth::id())->exists(),
            403,
            'Accès non autorisé à cette inscription.'
        );
    }
}

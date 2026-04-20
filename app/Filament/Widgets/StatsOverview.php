<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEvents = Event::count();
        $publishedEvents = Event::where('status', 'publié')->count();
        $totalRegistrations = Registration::where('status', 'validé')->count();
        $totalParticipants = User::where('role', 'participant')->count();

        $fillRate = $totalEvents > 0
            ? Event::withCount(['registrations' => fn ($q) => $q->where('status', 'validé')])
                ->get()
                ->avg(fn ($event) => $event->capacity > 0
                    ? ($event->registrations_count / $event->capacity) * 100
                    : 0)
            : 0;

        return [
            Stat::make('Événements publiés', $publishedEvents)
                ->description($totalEvents.' événements au total')
                ->color('success'),

            Stat::make('Inscriptions validées', $totalRegistrations)
                ->color('primary'),

            Stat::make('Participants inscrits', $totalParticipants)
                ->color('info'),

            Stat::make('Taux de remplissage moyen', round($fillRate, 1).'%')
                ->color('warning'),
        ];
    }
}

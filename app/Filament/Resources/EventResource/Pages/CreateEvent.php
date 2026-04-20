<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If organizer_id wasn't provided in the form (e.g. admin left it empty),
        // default to the currently authenticated user to avoid DB constraint errors.
        if (empty($data['organizer_id'])) {
            $data['organizer_id'] = Auth::id();
        }

        return $data;
    }
}

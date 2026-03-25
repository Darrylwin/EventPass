<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'starts_at',
        'location',
        'capacity',
        'price',
        'image_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'price' => 'decimal:2',
            'capacity' => 'integer',
        ];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Événements dont la date de début est dans le futur.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>', now());
    }

    /**
     * Événements dont la date de début est passée.
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('starts_at', '<=', now());
    }

    /**
     * Événements dont toutes les places sont prises.
     */
    public function scopeFull(Builder $query): Builder
    {
        return $query->whereRaw(
            "capacity <= (select count(*) from registrations where registrations.event_id = events.id and registrations.status = ?)",
            ['validé']
        );
    }

    public function isFull(): bool
    {
        return $this->registrations()->where('status', 'validé')->count() >= $this->capacity;
    }

    public function availableSpots(): int
    {
        $taken = $this->registrations()->where('status', 'validé')->count();
        return max(0, $this->capacity - $taken);
    }

    public function isFree(): bool
    {
        return (float)$this->price === 0.0;
    }
}

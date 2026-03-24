<?php

namespace App\Models;

use App\Enums\Degree;
use App\Enums\Prefix;
use Database\Factories\ParticipantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    /** @use HasFactory<ParticipantFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'prefix' => Prefix::class,
            'degree' => Degree::class,
            'lodge_number' => 'integer',
            'friday_dinner_count' => 'integer',
            'symposium_lunch_count' => 'integer',
            'companion_lunch_count' => 'integer',
            'ritual_participation' => 'boolean',
            'ball_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Participant $participant): void {
            $participant->registration->load('participants')->recalculateTotal();
        });

        static::deleted(function (Participant $participant): void {
            $participant->registration->load('participants')->recalculateTotal();
        });
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * @param  array<string, int>|null  $prices
     */
    public function calculateCost(?array $prices = null): int
    {
        return self::computeCost([
            'friday_dinner_count' => $this->friday_dinner_count,
            'symposium_lunch_count' => $this->symposium_lunch_count,
            'companion_lunch_count' => $this->companion_lunch_count,
            'ball_count' => $this->ball_count,
        ], $prices);
    }

    /**
     * @param  array{friday_dinner_count?: int, symposium_lunch_count?: int, companion_lunch_count?: int, ball_count?: int}  $data
     * @param  array<string, int>|null  $prices
     */
    public static function computeCost(array $data, ?array $prices = null): int
    {
        $prices ??= [
            'friday_dinner' => config('simpozion.events.friday_dinner.price'),
            'symposium_lunch' => config('simpozion.events.symposium_lunch.price'),
            'companion_lunch' => config('simpozion.events.companion_lunch.price'),
            'ball' => config('simpozion.events.ball.price'),
        ];

        return (($data['friday_dinner_count'] ?? 0) * $prices['friday_dinner'])
            + (($data['symposium_lunch_count'] ?? 0) * $prices['symposium_lunch'])
            + (($data['companion_lunch_count'] ?? 0) * $prices['companion_lunch'])
            + (($data['ball_count'] ?? 0) * $prices['ball']);
    }
}

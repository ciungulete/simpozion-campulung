<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Database\Factories\RegistrationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Registration extends Model
{
    /** @use HasFactory<RegistrationFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'payment_status' => PaymentStatus::class,
            'total_amount' => 'integer',
            'paid_amount' => 'integer',
        ];
    }

    public function remainingAmount(): int
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    protected static function booted(): void
    {
        static::creating(function (Registration $registration): void {
            $registration->uuid ??= Str::uuid();
        });
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function recalculateTotal(): void
    {
        $this->total_amount = $this->participants->sum(
            fn (Participant $participant) => $participant->calculateCost()
        );

        $this->saveQuietly();
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function shortReference(): string
    {
        return strtoupper(substr($this->uuid, 0, 8));
    }
}

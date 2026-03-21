<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Revolut = 'revolut';
    case Bcr = 'bcr';
    case Cash = 'cash';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'În așteptare',
            self::Revolut => 'Revolut',
            self::Bcr => 'BCR',
            self::Cash => 'Cash',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Revolut => 'info',
            self::Bcr => 'success',
            self::Cash => 'gray',
        };
    }

    public function isPaid(): bool
    {
        return $this !== self::Pending;
    }
}

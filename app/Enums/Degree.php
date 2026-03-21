<?php

namespace App\Enums;

enum Degree: string
{
    case Ucenic = 'ucenic';
    case Calfa = 'calfa';
    case Maestru = 'maestru';

    public function label(): string
    {
        return match ($this) {
            self::Ucenic => 'Ucenic',
            self::Calfa => 'Calfa',
            self::Maestru => 'Maestru',
        };
    }
}

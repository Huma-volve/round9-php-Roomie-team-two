<?php

namespace App\Enums;

enum Status: string
{
    case AVAILABLE = 'available';
    case UNAVAILABLE = 'unavailable';

    public static function options(): array
    {
        return [
            self::AVAILABLE->value => 'available',
            self::UNAVAILABLE->value => 'unavailable',
        ];
    }
}

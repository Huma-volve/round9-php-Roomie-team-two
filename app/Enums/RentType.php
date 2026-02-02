<?php

namespace App\Enums;

enum RentType: string
{
    case ROOM = 'room';
    case APARTMENT = 'apartment';

    public static function options(): array
    {
        return [
            self::ROOM->value => 'room',
            self::APARTMENT->value => 'apartment',
        ];
    }
}
<?php

namespace App\Enums;

enum RoomType: string
{
    case PRIVATE = 'private';
    case SHARED = 'shared';

    public static function options(): array
    {
        return [
            self::PRIVATE->value => 'private',
            self::SHARED->value => 'shared',
        ];
    }
}
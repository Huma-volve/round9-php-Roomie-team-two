<?php

namespace App\Enums;

enum RoomBedType: string
{
    case KING = 'king';
    case QUEEN = 'queen';
    case SINGLE = 'single';
    case DOUBLE = 'double';
    case TRIPLE = 'triple';
    case QUAD = 'quad';

    public static function options(): array
    {
        return [
            self::KING->value => 'king',
            self::QUEEN->value => 'queen',
            self::SINGLE->value => 'single',
            self::DOUBLE->value => 'double',
            self::TRIPLE->value => 'triple',
            self::QUAD->value => 'quad',
        ];
    }
}
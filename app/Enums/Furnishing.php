<?php

namespace App\Enums;

enum Furnishing: string
{
    case FURNISHED = 'furnished';
    case UNFURNISHED = 'unfurnished';
    case SEMI_FURNISHED = 'semi-furnished';

    public static function options(): array
    {
        return [
            self::FURNISHED->value => 'furnished',
            self::UNFURNISHED->value => 'unfurnished',
            self::SEMI_FURNISHED->value => 'semi-furnished',
        ];
    }
}
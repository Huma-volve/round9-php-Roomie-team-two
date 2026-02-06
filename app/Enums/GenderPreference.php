<?php

namespace App\Enums;

enum GenderPreference: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case BOTH = 'both';

    public static function options(): array
    {
        return [
            self::MALE->value => 'male',
            self::FEMALE->value => 'female',
            self::BOTH->value => 'both',
        ];
    }
}
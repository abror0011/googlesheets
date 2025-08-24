<?php

namespace App;

enum StatusEnum: int
{
    case ALLOWED    = 1;
    case PROHIBITED = 0;

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(function ($case) {
            return [$case->value => $case->name];
        })->toArray();
    }
}

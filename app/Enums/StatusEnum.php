<?php

namespace App\Enums;

enum StatusEnum: string
{
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case ACTIVE = 'active';

    /**
     * Get the values of the enum.
     *
     * @return array<string>
     */
    public static function toArray(): array
    {
        return [
            self::INACTIVE->value,
            self::PENDING->value,
            self::ACTIVE->value,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusEnum: int
{
    case APPROVED = 1;
    case REJECTED = 2;
    case PENDING = 3;

    public static function fromName(string $name): self
    {
        return match(strtolower($name)) {
            'approved' => self::APPROVED,
            'rejected' => self::REJECTED,
            'pending' => self::PENDING,
            default => throw new \InvalidArgumentException("Unknown status name: $name"),
        };
    }
}

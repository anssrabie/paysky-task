<?php

namespace App\Enum;

class OrderStatus extends BaseEnumeration
{
    public const PENDING = 'pending';
    public const COMPLETED = 'completed';

    public static function getList(): array
    {
        return [
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
        ];
    }

    public static function isPending(string $value): bool
    {
        return self::is($value, 'PENDING');
    }

    public static function isCompleted(string $value): bool
    {
        return self::is($value, 'COMPLETED');
    }

    public static function getPending(): string
    {
        return self::PENDING;
    }

    public static function getCompleted(): string
    {
        return self::COMPLETED;
    }
}

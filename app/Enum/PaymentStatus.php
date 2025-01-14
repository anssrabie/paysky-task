<?php

namespace App\Enum;

class PaymentStatus extends BaseEnumeration
{
    public const PENDING = 'pending';
    public const SUCCESSFUL = 'successful';
    public const FAILED = 'failed';


    public static function getList(): array
    {
        return [
            self::PENDING => 'Pending',
            self::SUCCESSFUL => 'Successful',
            self::FAILED => 'Failed',
        ];
    }

    public static function isPending(string $value): bool
    {
        return self::is($value, 'PENDING');
    }

    public static function isSuccessful(string $value): bool
    {
        return self::is($value, 'SUCCESSFUL');
    }

    public static function isFailed(string $value): bool
    {
        return self::is($value, 'FAILED');
    }


    public static function getPending(): string
    {
        return self::PENDING;
    }

    public static function getSuccessful(): string
    {
        return self::SUCCESSFUL;
    }

    public static function getFailed(): string
    {
        return self::FAILED;
    }


}

<?php

namespace App\Enum;

use Exception;
use Illuminate\Support\Facades\Log;
use ReflectionClassConstant;

class BaseEnumeration
{
    public static function getKeyList(): array
    {
        return array_keys(static::getList());
    }

    public static function getListExcept(int $key): array
    {
        $data = self::getList();
        unset($data[$key]);
        return $data;
    }

    public static function getKeyListExcept($exceptKey): array
    {
        $allKeys = array_keys(static::getList());
        return array_diff($allKeys, [$exceptKey]);
    }

    public static function getList(): array
    {
        return [];
    }

    /**
     * @return int|string|null
     */
    public static function getCode($statusWord)
    {
        // Loop through the status map and find the matching code
        foreach (static::getList() as $code => $word) {

            if (strcasecmp($word, $statusWord) === 0) {
                return $code;
            }
        }

        return null; // Return null or throw an exception if no match is found
    }

    public static function is(string $value, string $constant): bool
    {
        return self::getConstantValue($constant) === $value;
    }

    /**
     * @return mixed|null
     */
    public static function getConstantValue(string $constant)
    {
        try {
            $constant_reflex = new ReflectionClassConstant(get_called_class(), $constant);

            return $constant_reflex->getValue();
        } catch (Exception $e) {
            Log::error($e);
        }

        return null;
    }

    public static function getValue($val): string
    {
        return array_key_exists($val, static::getList()) ? static::getList()[$val] : '';
    }
}

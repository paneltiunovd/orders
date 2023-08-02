<?php

namespace app\models\Enums\Traits;

use ReflectionClass;
use ReflectionException;

trait Enumerable
{

    public static function byName(string $string, $default = '')
    {
        try{
            $ref = self::selfReflect();
            return $ref->getConstant($string);
        } catch (ReflectionException $e) {
            return $default;
        }
    }

    public static function getValues(): array
    {
        return array_filter(array_values(self::getConstants()), function ($constValue) {
            return is_int($constValue);
        });
    }

    public static function getConstants(): array
    {
        return self::selfReflect()->getConstants();
    }

    public static function getKeys(): array
    {
        return array_keys(self::getConstants());
    }

    private static function selfReflect(): ReflectionClass
    {
        return new ReflectionClass(self::class);
    }

    /**
     * @param $type
     * @return string|null
     */
    public static function byValue($type): ?string
    {
        foreach (self::getConstants() as $key => $value) {
            if($value == $type) {
                return $key;
            }
        }
        return null;
    }

}

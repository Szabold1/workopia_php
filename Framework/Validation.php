<?php

namespace Framework;

class Validation
{
    /**
     * Validate a string
     * @param string $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function string($value, $min = 1, $max = INF)
    {
        if (is_string($value)) {
            $length = strlen(trim($value));
            return $length >= $min && $length <= $max;
        }
        return false;
    }

    /**
     * Validate an email address
     * @param string $value
     * @return mixed
     */
    public static function email($value)
    {
        return filter_var(trim($value), FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check if a value is equal to another
     * @param string $value1
     * @param string $value2
     * @return bool
     */
    public static function match($value1, $value2)
    {
        return trim($value1) === trim($value2);
    }
}

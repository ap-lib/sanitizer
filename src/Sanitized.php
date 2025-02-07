<?php declare(strict_types=1);

namespace AP\Sanitizer;

readonly class Sanitized
{
    public array|string|int|float|bool|null $value;

    public function __construct(mixed $value)
    {
        $this->value = self::sanitize($value);
    }

    private static function sanitize(mixed $value): array|string|int|float|bool|null
    {
        if (is_string($value) || is_int($value) || is_float($value) || is_bool($value) || is_null($value)) {
            return $value;
        } elseif (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = self::sanitize($v);
            }
            return $value;
        }
        return null;
    }
}
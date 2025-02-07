<?php declare(strict_types=1);

namespace AP\Sanitizer;

interface Sanitizer
{
    public function sanitize(mixed $value): ?Sanitized;
}
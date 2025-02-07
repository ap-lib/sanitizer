<?php declare(strict_types=1);

namespace AP\Sanitizer;

use UnexpectedValueException;

class BaseSanitizer implements Sanitizer
{
    /**
     * @var array<Sanitizer>
     */
    protected array $sanitizers = [];

    /**
     * @param array<Sanitizer> $sanitizers
     */
    public function __construct(array $sanitizers = [])
    {
        foreach ($sanitizers as $sanitizer) {
            if ($sanitizer instanceof Sanitizer) {
                $this->appendSanitizer($sanitizer);
            } else {
                throw new UnexpectedValueException("all sanitizers must implement Sanitizer interface");
            }
        }
    }

    /**
     * @return array<Sanitizer>
     */
    final public function getSanitizers(): array
    {
        return $this->sanitizers;
    }

    /**
     * Adds a formatter to the list of formatters.
     *
     * @param Sanitizer $sanitizer
     * @return static
     */
    final public function appendSanitizer(Sanitizer $sanitizer): static
    {
        $this->sanitizers[] = $sanitizer;
        return $this;
    }

    /**
     * Prepends a formatter to the list, ensuring it is applied first.
     *
     * @param Sanitizer $sanitizer
     * @return static
     */
    final public function prependSanitizer(Sanitizer $sanitizer): static
    {
        $this->sanitizers = array_merge([$sanitizer], $this->sanitizers);
        return $this;
    }

    public function sanitize(mixed $value): ?Sanitized
    {
        if (is_string($value) || is_int($value) || is_float($value) || is_bool($value) || is_null($value)) {
            return new Sanitized($value);
        } elseif (is_array($value)) {
            foreach ($value as $k => $v) {
                $v = $this->sanitize($v);
                if ($v instanceof Sanitized) {
                    $value[$k] = $v->value;
                } else {
                    unset($value[$k]);
                }
            }
            return new Sanitized($value);
        }
        foreach ($this->sanitizers as $sanitizer) {
            $v = $sanitizer->sanitize($value);
            if ($v instanceof Sanitized) {
                return $v;
            }
        }
        return null;
    }
}
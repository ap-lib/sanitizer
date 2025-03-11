<?php declare(strict_types=1);

namespace AP\Sanitizer;

use Throwable;

readonly class ThrowableSanitizer implements Sanitizer
{
    /**
     * @param bool $include_trace
     * @param int $previous_entries_count Number of previous occurrences to include (integer instead of boolean for recursion protection).
     */
    public function __construct(
        public bool $include_trace = false,
        public int  $previous_entries_count = 0,
    )
    {
    }

    public function sanitize(mixed $value): ?Sanitized
    {
        return $value instanceof Throwable ?
            new Sanitized($this->sanitizeThrowable($value)) :
            null;
    }

    public function sanitizeThrowable(Throwable $value, ?int $previous_entries_count = null): array
    {
        $data = [
            'type'    => $value::class,
            'message' => $value->getMessage(),
            'file'    => $value->getFile(),
            'line'    => $value->getLine(),
            'code'    => $value->getCode(),
        ];

        if ($this->include_trace) {
            $data['trace'] = $value->getTrace();
        }
        if ($value->getPrevious() instanceof Throwable) {
            $previous_entries_count = is_null($previous_entries_count) ?
                $this->previous_entries_count :
                $previous_entries_count;

            if ($previous_entries_count > 0) {
                $data['previous'] = $this->sanitizeThrowable(
                    $value->getPrevious(),
                    $previous_entries_count - 1
                );
            }
        }

        return $data;
    }
}

<?php declare(strict_types=1);

namespace AP\Sanitizer\Tests;

use AP\Sanitizer\BaseSanitizer;
use AP\Sanitizer\ThrowableSanitizer;
use Exception;
use PHPUnit\Framework\TestCase;

final class ReadmeTest extends TestCase
{

    public function testSpecialSanitizer(): void
    {
        $sanitizer = new BaseSanitizer([
            new ThrowableSanitizer(include_trace: false)
        ]);

        $exception = new Exception("file not found", 1543);

        $sanitizedObject = $sanitizer->sanitize([
            "message"   => "some error message",
            "exception" => $exception,
        ]);

        $sanitizedArray = $sanitizedObject->value;

        // because filename can be different remove it before check result
        unset($sanitizedArray['exception']['file']);

        $this->assertEquals(
            [
                'message'   => 'some error message',
                'exception' =>
                    [
                        'type'    => 'Exception',
                        'message' => 'file not found',
                        // 'file'    => '/code/sanitizer/tests/ReadmeTest.php',
                        'line'    => 19,
                        'code'    => 1543,
                    ],
            ],
            $sanitizedArray
        );
    }


}

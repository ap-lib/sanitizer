<?php declare(strict_types=1);

namespace AP\Sanitizer\Tests;

use AP\Sanitizer\BaseSanitizer;
use AP\Sanitizer\Sanitized;
use AP\Sanitizer\ThrowableSanitizer;
use Exception;
use PHPUnit\Framework\TestCase;

final class BaseTest extends TestCase
{
    public function testBasic(): void
    {
        $sanitizer = new BaseSanitizer();

        $test = [
            "int"    => 7,
            "float"  => 3.14,
            "string" => "some string",
            "bool"   => true,
            "null"   => null,
            "array"  => [
                "int"    => 8,
                "float"  => 2.41,
                "string" => "some string",
                "bool"   => true,
                "null"   => null,
                "array"  => [
                    "int"    => 9,
                    "float"  => 3.14,
                    "string" => "some string",
                    "bool"   => true,
                    "null"   => null,
                ]
            ]
        ];

        $result = $sanitizer->sanitize($test);


        $this->assertEquals(new Sanitized($test), $result);
    }

    public function testRemoveNoAllowedElements(): void
    {
        $sanitizer = new BaseSanitizer();

        $test = [
            "message"   => "some error message",
            "level"     => 2,
            "exception" => new Exception("file not found", 1543),
        ];

        $result = $sanitizer->sanitize($test);

        $this->assertEquals(
            new Sanitized([
                "message" => "some error message",
                "level"   => 2,
            ]),
            $result
        );
    }

    public function testSpecialSanitizer(): void
    {
        $throwableSanitizer = new ThrowableSanitizer(include_trace: false,);
        $sanitizer          = new BaseSanitizer([$throwableSanitizer]);
        $exception          = new Exception("file not found", 1543);

        $this->assertEquals(
            new Sanitized([
                "message"   => "some error message",
                "exception" => $throwableSanitizer->sanitizeThrowable($exception)
            ]),
            $sanitizer->sanitize([
                "message"   => "some error message",
                "exception" => $exception,
            ])
        );
    }


}

<?php declare(strict_types=1);

namespace AP\Sanitizer\Tests;

use AP\Sanitizer\Sanitized;
use PHPUnit\Framework\TestCase;

final class SanitizedTest extends TestCase
{
    public function testBasic(): void
    {
        $base = [
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

        $this->assertEquals(
            $base,
            (new Sanitized($base))->value
        );
    }

    public function testSanitize(): void
    {
        $base = [
            "int"    => 7,
            "object" => new \Exception("hello world"),
            "array"  => [
                "object" => new \Exception("hello world2"),
            ]
        ];

        $this->assertEquals(
            [
                "int"    => 7,
                "object" => null,
                "array"  => [
                    "object" => null,
                ]
            ],
            (new Sanitized($base))->value
        );
    }
}

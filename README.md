# AP\Sanitizer

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A library that normalizes some mixed variable to simple types: int, string, array, bool, null

## Installation

```bash
composer require ap-lib/sanitizer
```

## Features

- Allowed custom sanitizers

## Requirements

- PHP 8.3 or higher

## Getting started

```php
$sanitizer = new BaseSanitizer([
    new ThrowableSanitizer(include_trace: false)
]);



$sanitizedObject = $sanitizer->sanitize([
    "message"   => "some error message",
    "exception" => new Exception("file not found", 1543),
]);

$sanitizedArray = $sanitizedObject->value;

var_export($sanitizedArray);
/*
[
    'message'   => 'some error message',
    'exception' =>
        [
            'type'    => 'Exception',
            'message' => 'file not found',
            'file'    => '/code/path/to/file.php',
            'line'    => 19,
            'code'    => 1543,
        ],
]
*/
```
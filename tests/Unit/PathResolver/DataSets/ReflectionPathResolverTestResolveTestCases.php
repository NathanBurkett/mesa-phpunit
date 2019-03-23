<?php

use PHPUnit\Framework\TestCase;

return [
    'ExampleTestCases.php is found successfully' => [
        'path' => 'ExampleTestCases.php',
        'reflectionClassHandler' => function (TestCase $testCase): ReflectionClass {
            return new \ReflectionClass($testCase);
        },
        'expected' => realpath(__DIR__ . '/../ReflectionPathResolverTest/ExampleTestCases.php'),
        'expectedException' => null,
    ],
    'absolute path is found and returns early successfully' => [
        'path' => realpath(__DIR__ . '/../ReflectionPathResolverTest/ExampleTestCases.php'),
        'reflectionClassHandler' => function (TestCase $testCase): ReflectionClass {
            return new \ReflectionClass($testCase);
        },
        'expected' => realpath(__DIR__ . '/../ReflectionPathResolverTest/ExampleTestCases.php'),
        'expectedException' => null,
    ],
    'ReflectionPathResolverTest/ExampleTestCases.php is sanitized and found successfully' => [
        'path' => 'ExampleTestCases.php',
        'reflectionClassHandler' => function (TestCase $testCase): ReflectionClass {
            return new \ReflectionClass($testCase);
        },
        'expected' => realpath(__DIR__ . '/../ReflectionPathResolverTest/ExampleTestCases.php'),
        'expectedException' => null,
    ],
    'throws UnexpectedValueException because of non-reflectable class' => [
        'path' => 'ExampleTestCases.php',
        'reflectionClassHandler' => function (TestCase $testCase): ReflectionClass {
            return new \ReflectionClass(new ArrayIterator());
        },
        'expected' => __DIR__ . '/ExampleTestCases.php',
        'expectedException' => \UnexpectedValueException::class,
    ],
    'throws Exception because of not found file' => [
        'path' => 'FooTestCases.php',
        'reflectionClassHandler' => function (TestCase $testCase): ReflectionClass {
            return new \ReflectionClass($testCase);
        },
        'expected' => __DIR__ . '/FooTestCases.php',
        'expectedException' => \Exception::class,
    ],
];

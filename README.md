# PHPUnit Mesa

[![CircleCI](https://circleci.com/gh/NathanBurkett/mesa-phpunit.svg?style=svg)](https://circleci.com/gh/NathanBurkett/mesa-phpunit) [![codecov](https://codecov.io/gh/NathanBurkett/mesa-phpunit/branch/master/graph/badge.svg)](https://codecov.io/gh/NathanBurkett/mesa-phpunit)

PHPUnit Mesa is a table-driven approach to orchestrating data providers in PHPUnit

## Installation

Via composer

``` bash
composer require nathanburkett/mesa-phpunit --dev
```

## Usage

### Via [`GeneratesTestCases` Trait](src/GeneratesTestCases.php)

Using the `GeneratesTestCases` trait is the simplest way to start consuming tables in PHPUnit.

```php
<?php namespace Foo\Bar\Baz;

use PHPUnit\Framework\TestCase;
use NathanBurkett\Mesa\Fixture\PHPUnit\GeneratesTestCases;

class FooTest extends TestCase
{
    use GeneratesTestCases;
}
```

Given a php file of test cases:
```php
<?php

// Location __DIR__ . '/DataSets/FooTest/OutputTestCases.php

use PHPUnit\Framework\TestCase;
use PHPUnit\MockObject\MockObject;

return [
    'Expects \'foo\' output' => [
        'setupDependencyOneExpectations' => function (TestCase $test, MockObject $dependencyOne): MockObject {
            $dependencyOne->expects($test->once())->method('foo')->willReturn(true);
            return $dependencyOne;
        },
        'config' => 'Some config',
        'expected' => 'FooBarBaz',
    ],
];

```

Then build a table targeting a data provider like so:
```php
<?php namespace Foo\Bar\Baz;

use PHPUnit\Framework\TestCase;
use NathanBurkett\Mesa\Fixture\PHPUnit\GeneratesTestCases;

class FooTest extends TestCase
{
    use GeneratesTestCases;
    
    /**
     * @dataProvider generateOutputTestCases
     * 
     * @param DependencyOne $dependencyOne
     * @param string $config
     * @param string $expected
     */
    public function testOutput(DependencyOne $dependencyOne, string $config, string $expected)
    {
        $service = new FooService($dependencyOne, $config);
        $actual = $service->run();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @return \Generator
     */
    public function generateOutputTestCases(): \Generator
    {
        yield from $this->generateTestCases(
            'OutputTestCases.php' // this defaults to looking for test cases in __DIR__ . /DataSets/{ClassName}/{string}
            // [$this, 'setupOutputTestCase'] Optional setup func
        );
    }
    
    /**
     * @param array $testCase
     * @return array
     */
    public function setupOutputTestCase(array $testCase): array
    {
        $dependencyOne = $this->getMockBuilder(DependencyOne::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        
        return [
            $testCase['setupDependencyOneExpectations']($this, $dependencyOne),
            $testCase['config'],
            $testCase['expected'],
        ];
    }
}
```

To change default directory where data sets are resolved on a class by class basis, extend [PHPUnitDataSetPathResolver](src/PathResolver/PHPUnitDataSetPathResolver.php) and supply a different value for `DEFAULT_DIRECTORY` constant:

```php
<?php namespace Foo\Bar\Baz;

use NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver\PHPUnitDataSetPathResolver;

class NewPHPUnitPathResolver extends PHPUnitDataSetPathResolver
{
    /**
     * @var string 
     */
    public const DEFAULT_DIRECTORY = 'TestCases';
}
```

And inside your testing class:

```php
<?php namespace Foo\Bar\Baz;

use PHPUnit\Framework\TestCase;
use Foo\Bar\Baz\NewPHPUnitPathResolver;
use NathanBurkett\Mesa\Fixture\PHPUnit\GeneratesTestCases;
use NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver\PathResolver;

class FooTest extends TestCase
{
    use GeneratesTestCases;
    
    /**
     * @return PathResolver
     */
    protected function getTestCasePathResolver(): PathResolver
    {
        return new NewPHPUnitPathResolver((string) $this->testCaseContext, new \ReflectionClass($this));
    }
} 
```

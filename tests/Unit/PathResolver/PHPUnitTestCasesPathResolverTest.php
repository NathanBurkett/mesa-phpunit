<?php namespace NathanBurkett\Mesa\Fixture\PHPUnit\Tests\Unit\PathResolver;

use NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver\PHPUnitDataSetPathResolver;
use PHPUnit\Framework\TestCase;

class PHPUnitTestCasesPathResolverTest extends TestCase
{
    /**
     * @dataProvider generatesResolveTestCases
     *
     * @param string $path
     * @param \ReflectionClass $reflect
     * @param string $expected
     * @param string|null $expectedException
     */
    public function testResolve(
        string $path,
        \ReflectionClass $reflect,
        string $expected,
        string $expectedException = null
    ) {
        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $actual = (new PHPUnitDataSetPathResolver($path, $reflect))->resolve();

        if (!$expectedException) {
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * @return \Generator
     */
    public function generatesResolveTestCases(): \Generator
    {
        $testCases = require_once __DIR__ . '/DataSets/PHPUnitTestCasesPathResolverTestResolveTestCases.php';

        foreach ($testCases as $name => $testCase) {
            yield $name => $this->setupResolveTestCase($testCase);
        }
    }

    /**
     * @param array $testCase
     *
     * @return array
     */
    protected function setupResolveTestCase(array $testCase): array
    {
        return [
            $testCase['path'],
            $testCase['reflectionClassHandler']($this),
            $testCase['expected'],
            $testCase['expectedException'] ?? null,
        ];
    }
}

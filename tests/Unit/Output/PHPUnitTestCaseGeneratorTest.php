<?php namespace NathanBurkett\Mesa\Fixture\PHPUnit\Tests\Unit\Output;

use NathanBurkett\Mesa\Fixture\PHPUnit\Output\PHPUnitTestCaseGenerator;
use NathanBurkett\Mesa\Output\OutputStrategy;
use PHPUnit\Framework\TestCase;

class PHPUnitTestCaseGeneratorTest extends TestCase
{
    /**
     * @var OutputStrategy
     */
    private $outputStrategy;

    protected function setUp()
    {
        $this->outputStrategy = new PHPUnitTestCaseGenerator();
    }

    /**
     * @dataProvider generatesProduceTestCases
     *
     * @param iterable $table
     * @param callable $setup
     * @param array $expected
     * @param string|null $expectedException
     */
    public function testProduce(
        iterable $table,
        array $expected,
        callable $setup = null,
        string $expectedException = null
    ) {
        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $actual = [];

        foreach ($this->outputStrategy->produce($table, $setup) as $key => $value) {
            $actual[$key] = $value;
        }

        if (!$expectedException) {
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * @return \Generator
     */
    public function generatesProduceTestCases(): \Generator
    {
        $testCases = require_once __DIR__ . '/DataSets/PHPUnitTestCaseGeneratorTest/ProduceTestCases.php';

        foreach ($testCases as $name => $testCase) {
            yield $name => $this->setupProduceTestCase($testCase);
        }
    }

    private function setupProduceTestCase(array $testCase)
    {
        return [
            $testCase['iterable'],
            $testCase['expected'],
            $testCase['setup'] ?? function (array $row): array { return $row; },
            $testCase['expectedException'] ?? null,
        ];
    }
}

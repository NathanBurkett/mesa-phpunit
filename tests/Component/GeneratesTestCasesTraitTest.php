<?php namespace NathanBurkett\Mesa\Fixture\PHPUnit\Tests\Component;

use NathanBurkett\Mesa\Fixture\PHPUnit\GeneratesTestCases;
use NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver\PathResolver;
use NathanBurkett\Mesa\Output\OutputStrategy;
use PHPUnit\Framework\TestCase;

class GeneratesTestCasesTraitTest extends TestCase
{
    use GeneratesTestCases;

    public function testGetOutputStrategy()
    {
        $this->assertInstanceOf(OutputStrategy::class, $this->getOutputStrategy());
    }

    public function testGetTestCasePathResolver()
    {
        $this->testCaseContext = 'foo';
        $this->assertInstanceOf(PathResolver::class, $this->getTestCasePathResolver());
        $this->testCaseContext = null;
    }

    public function testGenerateTestCases()
    {
        $testCases = $this->generateTestCases('ExampleDataSets.php');

        $expected = [
            'one' => 'hello',
            'two' => 'world',
        ];

        $actual = [];
        foreach ($testCases as $key => $value) {
            $actual[$key] = $value;
        }

        $this->assertEquals($expected, $actual);
    }
}

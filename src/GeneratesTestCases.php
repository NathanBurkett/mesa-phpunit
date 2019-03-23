<?php namespace NathanBurkett\Mesa\Fixture\PHPUnit;

use NathanBurkett\Mesa\Fixture\PHPUnit\Output\PHPUnitTestCaseGenerator;
use NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver\PathResolver;
use NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver\PHPUnitDataSetPathResolver;
use NathanBurkett\Mesa\GeneratesTables;
use NathanBurkett\Mesa\Output\OutputStrategy;

trait GeneratesTestCases
{
    use GeneratesTables;

    /**
     * @var mixed
     */
    protected $testCaseContext;

    /**
     * @param mixed $tableContext
     * @param callable|null $rowSetup
     *
     * @return iterable
     * @throws \Exception
     */
    protected function generateTestCases($tableContext, callable $rowSetup = null): iterable
    {
        $this->testCaseContext = $tableContext;

        $pathResolver = $this->getTestCasePathResolver();

        return $this->generateTable($pathResolver->resolve(), $rowSetup);
    }

    /**
     * @return OutputStrategy
     */
    protected function getOutputStrategy(): OutputStrategy
    {
        return new PHPUnitTestCaseGenerator();
    }

    /**
     * @return PathResolver
     */
    protected function getTestCasePathResolver(): PathResolver
    {
        return new PHPUnitDataSetPathResolver((string) $this->testCaseContext, new \ReflectionClass($this));
    }
}

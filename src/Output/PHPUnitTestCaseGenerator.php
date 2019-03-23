<?php namespace NathanBurkett\Mesa\Fixture\PHPUnit\Output;

use NathanBurkett\Mesa\Output\OutputGenerator;

/**
 * Class PHPUnitTestCaseGenerator
 *
 * @package NathanBurkett\Mesa\Fixture\PHPUnit\Output
 */
class PHPUnitTestCaseGenerator extends OutputGenerator
{
    /**
     * @var array
     */
    const ERROR_MESSAGES = [
        JSON_ERROR_NONE => 'No error has occurred',
        JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
    ];

    /**
     * @param int|string $index
     * @param mixed $row
     * @param callable $setup
     *
     * @return \Generator
     */
    protected function handleIteration($index, $row, callable $setup): \Generator
    {
        if ($this->iterationRequiresName($index)) {
            $index = $this->encodeRow($row);
        }

        return parent::handleIteration($index, $row, $setup);
    }

    /**
     * @param string|int $index
     *
     * @return bool
     */
    protected function iterationRequiresName($index): bool
    {
        return gettype($index) === 'integer';
    }

    /**
     * Encode the row as the name.
     *
     * @param array $row
     *
     * @return string
     */
    protected function encodeRow($row): string
    {
        if (!$encodedRow = json_encode($row, JSON_UNESCAPED_SLASHES)) {
            throw new \RuntimeException(static::ERROR_MESSAGES[json_last_error()]);
        }

        return $encodedRow;
    }
}

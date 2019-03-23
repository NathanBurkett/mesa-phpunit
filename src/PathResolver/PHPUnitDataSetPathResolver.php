<?php namespace NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver;

/**
 * Class PHPUnitDataSetPathResolver
 *
 * @package NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver
 */
class PHPUnitDataSetPathResolver extends ReflectionPathResolver
{
    /**
     * @var string
     */
    const DEFAULT_DIRECTORY = 'DataSets';

    /**
     * @return array
     */
    protected function getPathSegments(): array
    {
        return [
            $this->getDirectorySegment(),
            static::DEFAULT_DIRECTORY,
            $this->getSubDirectorySegment(),
            $this->path
        ];
    }
}

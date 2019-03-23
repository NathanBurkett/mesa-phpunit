<?php namespace NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver;

/**
 * Class FilePathResolver
 *
 * @package NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver
 */
class FilePathResolver implements PathResolver
{
    /**
     * @var string
     */
    protected $path;

    /**
     * ReflectionPathResolver constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function resolve(): string
    {
        return $this->filePathOrFail($this->path);
    }

    /**
     * @param string $path
     *
     * @return string
     * @throws \Exception
     */
    protected function filePathOrFail(string $path): string
    {
        if (!$filePath = realpath($path)) {
            throw new \Exception(sprintf('Unable to locate \'%s\'', $path));
        }

        return $filePath;
    }
}

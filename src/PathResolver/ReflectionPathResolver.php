<?php namespace NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver;

/**
 * Class ReflectionPathResolver
 *
 * @package NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver
 */
class ReflectionPathResolver extends FilePathResolver
{
    /**
     * @var \ReflectionClass
     */
    protected $reflect;

    /**
     * ReflectionPathResolver constructor.
     *
     * @param string $path
     * @param \ReflectionClass $reflect
     */
    public function __construct(string $path, \ReflectionClass $reflect)
    {
        parent::__construct($path);
        $this->reflect = $reflect;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function resolve(): string
    {
        if ($path = realpath($this->path)) {
            return $path;
        }

        $this->verifyReflection();

        $this->buildPath();

        return parent::resolve();
    }

    /**
     * @throws \UnexpectedValueException
     */
    protected function verifyReflection(): void
    {
        if (!$this->reflect->getFileName()) {
            $message = 'Cannot extract details from PHP core object or extension: \'%s\'';
            throw new \UnexpectedValueException(sprintf($message, $this->reflect->getName()));
        }
    }

    protected function buildPath(): void
    {
        $this->sanitizePath();

        $this->path = $this->buildPathFromSegments();
    }

    protected function sanitizePath(): void
    {
        $this->path = trim($this->path, DIRECTORY_SEPARATOR);

        // Prevent subdirectory redundancy
        $this->path = trim($this->path, $this->getSubDirectorySegment() . DIRECTORY_SEPARATOR);
    }

    /**
     * @return string
     */
    protected function buildPathFromSegments(): string
    {
        return implode(DIRECTORY_SEPARATOR, $this->getPathSegments());
    }

    /**
     * @return array
     */
    protected function getPathSegments(): array
    {
        return [
            $this->getDirectorySegment(),
            $this->getSubDirectorySegment(),
            $this->path
        ];
    }

    /**
     * @return string
     */
    protected function getDirectorySegment(): string
    {
        return dirname((string) $this->reflect->getFileName());
    }

    /**
     * @return string
     */
    protected function getSubDirectorySegment(): string
    {
        return $this->reflect->getShortName();
    }
}

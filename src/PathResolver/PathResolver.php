<?php namespace NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver;

/**
 * Class ReflectionPathResolver
 *
 * @package NathanBurkett\Mesa\Fixture\PHPUnit\PathResolver
 */
interface PathResolver
{
    /**
     * @return string
     */
    public function resolve(): string;
}

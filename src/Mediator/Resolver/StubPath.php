<?php

namespace Mediator\Resolver;

use Mediator\PathResolver;

/**
 * Stub path resolver
 *
 * @package Mediator
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class StubPath implements PathResolver
{
    private $path;

    /**
     * Constructor
     *
     * @param  string $path The path that will be returned anyway
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($media, array $options = array())
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath($media, array $options = array())
    {
        return (string) $this->path;
    }
}

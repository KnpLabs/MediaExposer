<?php

namespace Mediator;

use Mediator\Iterator\SortedResolverIterator;
use Mediator\Iterator\SourceResolverFilterIterator;
use Mediator\Iterator\PathResolverFilterIterator;

/**
 * The exposer is responsible of returning sources and paths for your medias
 *
 * @package Mediator
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Exposer
{
    private $baseUrl;
    private $resolvers;

    public function __construct($baseUrl = null)
    {
        $this->baseUrl = $baseUrl;
        $this->resolvers = new \SplObjectStorage();
    }

    /**
     * Returns the source for the given media and options
     *
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return string
     */
    public function getSource($media, array $options = array(), $forceAbsolute = false)
    {
        $source = null;

        foreach ($this->getSortedSourceResolvers() as $resolver) {
            if ($resolver->supports($media, $options)) {
                $source = $resolver->getSource($media, $options);
                $sourceType = $resolver->getSourceType($media, $options);
            }
        }

        if (null === $source) {
            throw new \RuntimeException(
                'There is no source resolver for the given media and options.'
            );
        }

        if ($forceAbsolute && SourceResolver::TYPE_RELATIVE === $sourceType) {
            $source = $this->absolutify($source);
        }

        return $source;
    }

    /**
     * Returns the path for the given media and options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return string
     */
    public function getPath($media, array $options = array())
    {
        foreach ($this->getSortedPathResolvers() as $resolver) {
            if ($resolver->supports($media, $options)) {
                return $resolver->getPath($media, $options);
            }
        }

        throw new \RuntimeException(
            'There is no path resolver for the given media and options.'
        );
    }

    public function addResolver(Resolver $resolver, $priority = 0)
    {
        $this->resolvers[$resolver] = $priority;
    }

    /**
     * Returns all the registered resolvers in the same order they were added
     *
     * @return Traversable
     */
    public function getResolvers()
    {
        return new \IteratorIterator($this->resolvers);
    }

    /**
     * Returns all the registered resolvers sorted by priority
     *
     * @return Traversable
     */
    public function getSortedResolvers()
    {
        return new SortedResolverIterator($this->resolvers);
    }

    /**
     * Returns all the registerd source resolvers in the same order they were
     * added
     *
     * @return Traversable
     */
    public function getSourceResolvers()
    {
        return new SourceResolverFilterIterator($this->getResolvers());
    }

    /**
     * Returns all the registered source resolvers sorted by priority
     *
     * @return Traversable
     */
    public function getSortedSourceResolvers()
    {
        return new SourceResolverFilterIterator($this->getSortedResolvers());
    }

    /**
     * Returns all the registered path resolvers in the same order they were
     * added
     *
     * @return Traversable
     */
    public function getPathResolvers()
    {
        return new PathResolverFilterIterator($this->getResolvers());
    }

    /**
     * Returns all the registered path resolvers sorted by priority
     *
     * @return Traversable
     */
    public function getSortedPathResolvers()
    {
        return new PathResolverFilterIterator($this->getSortedResolvers());
    }

    /**
     * Converts the given relative path into an absolute URL
     *
     * @param  string $relativePath
     *
     * @return string The result absolute URL
     */
    private function absolutify($relativePath)
    {
        if (null === $this->baseUrl) {
            throw new \LogicException(sprintf(
                'Cannot absolutify the relative path \'%s\' as the base url is not configured.',
                $relativePath
            ));
        }

        return $this->baseUrl . $relativePath;
    }
}

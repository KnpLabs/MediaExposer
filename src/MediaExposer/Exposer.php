<?php

namespace MediaExposer;

use MediaExposer\Iterator\SortedResolverIterator;
use MediaExposer\Iterator\SourceResolverFilterIterator;
use MediaExposer\Iterator\PathResolverFilterIterator;

/**
 * The exposer is responsible of returning sources and paths for your medias.
 *
 * @package MediaExposer
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class Exposer
{
    private $baseUrl;
    private $resolvers;

    /**
     * Constructor
     *
     * Note: The $baseUrl is used to "absolutify" relative sources when the
     * $forceAbsolute argument of the ->getSource() method is set to TRUE and
     * the source returned by the resolver is relative.
     *
     * @param  string $baseUrl An optional base url
     */
    public function __construct($baseUrl = null)
    {
        $this->baseUrl = $baseUrl;
        $this->resolvers = new \SplObjectStorage();
    }

    /**
     * Defines the base url
     *
     * @param  string $url
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    /**
     * Indicates whether a source can be generated for the given media and
     * options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return Boolean
     */
    public function hasSource($media, array $options = array())
    {
        return null !== $this->getSourceResolver($media, $options);
    }

    /**
     * Returns the source for the given media and options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return string
     */
    public function getSource($media, array $options = array(), $forceAbsolute = false)
    {
        $resolver = $this->getSourceResolver($media, $options);

        if (null === $resolver) {
            throw new \RuntimeException(
                'There is no source resolver for the given media and options.'
            );
        }

        $source = $resolver->getSource($media, $options);

        if ($forceAbsolute && SourceResolver::TYPE_RELATIVE === $resolver->getSourceType($media, $options)) {
            $source = $this->absolutify($source);
        }

        return $source;
    }

    /**
     * Indicates whether a path can be generated for the given media and options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return Boolean
     */
    public function hasPath($media, array $options = array())
    {
        return null !== $this->getPathResolver($media, $options);
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
        $resolver = $this->getPathResolver($media, $options);

        if (null === $resolver) {
            throw new \RuntimeException(
                'There is no path resolver for the given media and options.'
            );
        }

        return (string) $resolver->getPath($media, $options);
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
     * Returns the first source resolver supporting the given media and options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return SourceResolver or NULL if no resolver were found
     */
    private function getSourceResolver($media, array $options)
    {
        foreach ($this->getSortedSourceResolvers() as $resolver) {
            if ($resolver->supports($media, $options)) {
                return $resolver;
            }
        }

        return null;
    }

    /**
     * Returns the first path resolver supporting the given media and options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return PathResolver or NULL if no resolver were found
     */
    private function getPathResolver($media, array $options)
    {
        foreach ($this->getSortedPathResolvers() as $resolver) {
            if ($resolver->supports($media, $options)) {
                return $resolver;
            }
        }

        return null;
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

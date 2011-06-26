<?php

namespace Mediator\Resolver;

use Mediator\SourceResolver;

/**
 * Stub source resolver
 *
 * @package Mediator
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class StubSource implements SourceResolver
{
    private $source;

    /**
     * Constructor
     *
     * @param  string $source       The source that will be returned anyway
     * @param  string $sourceType   The source type
     */
    public function __construct($source, $sourceType)
    {
        $this->source = $source;
        $this->sourceType = $sourceType;
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
    public function getSource($media, array $options = array())
    {
        return (string) $this->source;
    }

    /**
     * {@inheritDoc}
     */
    public function getSourceType($media, array $options = array())
    {
        return (string) $this->sourceType;
    }
}

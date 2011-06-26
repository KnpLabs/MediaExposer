<?php

namespace Mediator;

/**
 * Interface for the source resolvers
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
interface SourceResolver extends Resolver
{
    const TYPE_ABSOLUTE = 'absolute';
    const TYPE_RELATIVE = 'relative';

    /**
     * Returns the source for the given media and options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return string
     */
    function getSource($media, array $options);

    /**
     * Returns type of the source for the given media and options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return string One of the TYPE_* constants
     */
    function getSourceType($media, array $options);
}

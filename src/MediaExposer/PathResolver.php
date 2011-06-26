<?php

namespace MediaExposer;

/**
 * Interface for the path resolvers
 *
 * @package MediaExposer
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
interface PathResolver extends Resolver
{
    /**
     * Returns the path of the given media and options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return string
     */
    function getPath($media, array $options);
}

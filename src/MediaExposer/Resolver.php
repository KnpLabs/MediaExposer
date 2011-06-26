<?php

namespace MediaExposer;

/**
 * Interface for the resolvers
 *
 * @package MediaExposer
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
interface Resolver
{
    /**
     * Indicates whether the resolver supports the given media and options
     *
     * @param  mixed $media
     * @param  array $options
     *
     * @return boolean
     */
    public function supports($media, array $options);
}

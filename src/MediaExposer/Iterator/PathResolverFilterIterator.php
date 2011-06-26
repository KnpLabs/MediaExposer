<?php

namespace MediaExposer\Iterator;

use MediaExposer\PathResolver;

/**
 * Iterator that filters another iterator to only return the elements
 * implementing the PathResolver interface
 *
 * @package MediaExposer
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class PathResolverFilterIterator extends \FilterIterator
{
    /**
     * {@inheritDoc}
     */
    public function accept()
    {
        return $this->current() instanceof PathResolver;
    }
}

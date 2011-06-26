<?php

namespace Mediator\Iterator;

use Mediator\PathResolver;

/**
 * Iterator that filters another iterator to only return the elements
 * implementing the PathResolver interface
 *
 * @package Mediator
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

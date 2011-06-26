<?php

namespace Mediator\Iterator;

use Mediator\SourceResolver;

/**
 * Iterator that filters another iterator to only return the elements
 * implementing the SourceResolver interface
 *
 * @package Mediator
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class SourceResolverFilterIterator extends \FilterIterator
{
    /**
     * {@inheritDoc}
     */
    public function accept()
    {
        return $this->current() instanceof SourceResolver;
    }
}

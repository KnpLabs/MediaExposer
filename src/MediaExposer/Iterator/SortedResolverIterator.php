<?php

namespace MediaExposer\Iterator;

/**
 * Iterator that iterates over the items of the given SplObjectStorage sorted
 * by priority. The priority is the data associated to the objects
 *
 * @package MediaExposer
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class SortedResolverIterator extends \ArrayIterator
{
    /**
     * Constructor
     *
     * @param  SplObjectStorage $objectStorage
     */
    public function __construct(\SplObjectStorage $storage)
    {
        $groupedByPriority = array();

        foreach ($storage as $object) {
            $groupedByPriority[$storage[$object]][] = $object;
        }

        ksort($groupedByPriority);

        $sorted = array();
        foreach ($groupedByPriority as $priorityResolvers) {
            $sorted = array_merge($sorted, $priorityResolvers);
        }

        parent::__construct($sorted);
    }
}

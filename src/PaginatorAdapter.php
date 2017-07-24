<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

use MongoDB\Collection;
use MongoDB\Driver\Command;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use Zend\Paginator\Adapter\AdapterInterface;

class PaginatorAdapter implements AdapterInterface
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var array|object
     */
    protected $filter;

    /**
     * PaginatorAdapter constructor.
     * @param Manager $manager
     * @param Collection $collection
     * @param array|object $filter The search filter.
     */
    public function __construct(Manager $manager, Collection $collection, $filter)
    {
        $this->manager = $manager;
        $this->collection = $collection;
        $this->filter = $filter;
    }

    public function count()
    {
        return $this->collection->count($this->filter);
    }

    public function getItems($offset, $itemCountPerPage)
    {
        $query = new Query($this->filter, ['skip' => $offset, 'limit' => $itemCountPerPage]);
        $cursor = $this->manager->executeQuery($this->collection->getNamespace(), $query);
        $cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
        return $cursor;
    }
}

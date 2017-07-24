<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

use Countable;
use InvalidArgumentException;
use Iterator;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\Iterator\HydratingIteratorInterface;

class HydratingMongoCursor implements Countable, Iterator, HydratingIteratorInterface
{
    /**
     * @var HydratorInterface
     */
    protected $hydrator;
    protected $prototype;
    protected $count;
    protected $iterator = null;

    /**
     * HydratingMongoCursor constructor.
     * @param Manager $manager
     * @param $namespace
     * @param Query $query
     * @param HydratorInterface $hydrator
     * @param $prototype
     */
    public function __construct(Manager $manager, $namespace, Query $query, HydratorInterface $hydrator, $prototype)
    {
        $this->setHydrator($hydrator);
        $this->setPrototype($prototype);

        $cursor = $manager->executeQuery($namespace, $query);
        $cursor->setTypeMap(['root' => 'array', '__pclass' => get_class($prototype), 'array' => 'array']);

        $array = $cursor->toArray();

        $this->count = count($array);
        $this->iterator = new \ArrayIterator($array);
    }

    public function getPrototype()
    {
        return $this->prototype;
    }

    /**
     * This sets the prototype to hydrate.
     *
     * This prototype can be the name of the class or the object itself;
     * iteration will clone the object.
     *
     * @param string|object $prototype
     */
    public function setPrototype($prototype)
    {
        if (!is_object($prototype)) {
            throw new InvalidArgumentException(sprintf(
                'Prototype must be an object; received "%s"',
                gettype($prototype)
            ));
        }
        $this->prototype = $prototype;
    }

    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * Sets the hydrator to use during iteration.
     *
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function count()
    {
        return $this->count;
    }

    public function current()
    {
        $result = $this->iterator->current();
        if (!is_array($result)) {
            return $result;
        }

        return $this->hydrator->hydrate($result, clone $this->prototype);
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function rewind()
    {
        $this->iterator->rewind();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }
}

<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace PhlyMongo;

use MongoDB\Collection;
use MongoDB\Driver\Manager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MongoCollectionFactory implements FactoryInterface
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * @var string
     */
    protected $collectionName;

    /**
     * MongoCollectionFactory constructor.
     * @param Manager $manager
     * @param string $databaseName
     * @param string $collectionName
     */
    public function __construct($manager, $databaseName, $collectionName)
    {
        $this->manager           = $manager;
        $this->databaseName      = $databaseName;
        $this->collectionName    = $collectionName;
    }

    public function createService(ServiceLocatorInterface $services)
    {
        return new Collection($this->manager, $this->databaseName, $this->collectionName);
    }
}

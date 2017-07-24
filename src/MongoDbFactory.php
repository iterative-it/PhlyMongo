<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

use MongoDB\Database;
use MongoDB\Driver\Manager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MongoDbFactory implements FactoryInterface
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $dbName;

    /**
     * MongoDbFactory constructor.
     * @param Manager $manager
     * @param string $dbName
     */
    public function __construct($manager, $dbName)
    {
        $this->manager  = $manager;
        $this->dbName   = $dbName;
    }

    public function createService(ServiceLocatorInterface $services)
    {
        return new Database($this->manager, $this->dbName);
    }
}

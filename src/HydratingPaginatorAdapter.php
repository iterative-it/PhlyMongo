<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 * @copyright Copyright (c) 2014 Matthew Weier O'Phinney
 */

namespace PhlyMongo;

use Zend\Paginator\Adapter\AdapterInterface;

class HydratingPaginatorAdapter implements AdapterInterface
{
    protected $cursor;
    protected $hydrator;
    protected $prototype;

    public function __construct(HydratingMongoCursor $cursor)
    {
        //parent::__construct($cursor);

        $this->cursor    = $cursor;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return $this->cursor->count();
    }

    public function getItems($offset, $itemCountPerPage)
    {
        $this->cursor->skip($offset);
        $this->cursor->limit($itemCountPerPage);
        return $this->cursor;
    }
}

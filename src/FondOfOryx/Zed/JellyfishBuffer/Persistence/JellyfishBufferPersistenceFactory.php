<?php

namespace FondOfOryx\Zed\JellyfishBuffer\Persistence;

use FondOfOryx\Zed\JellyfishBuffer\Persistence\Propel\Mapper\JellyfishBufferMapper;
use FondOfOryx\Zed\JellyfishBuffer\Persistence\Propel\Mapper\JellyfishBufferMapperInterface;
use Orm\Zed\JellyfishBuffer\Persistence\Base\FooExportedOrderQuery as OrmFooExportedOrderQuery;
use Orm\Zed\JellyfishBuffer\Persistence\FooExportedOrderHistoryQuery;
use Orm\Zed\JellyfishBuffer\Persistence\FooExportedOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \FondOfOryx\Zed\JellyfishBuffer\Persistence\JellyfishBufferEntityManagerInterface getEntityManager()
 * @method \FondOfOryx\Zed\JellyfishBuffer\JellyfishBufferConfig getConfig()
 * @method \FondOfOryx\Zed\JellyfishBuffer\Persistence\JellyfishBufferRepositoryInterface getRepository()
 */
class JellyfishBufferPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \FondOfOryx\Zed\JellyfishBuffer\Persistence\Propel\Mapper\JellyfishBufferMapperInterface
     */
    public function createJellyfishBufferMapper(): JellyfishBufferMapperInterface
    {
        return new JellyfishBufferMapper();
    }

    /**
     * @return \Orm\Zed\JellyfishBuffer\Persistence\Base\FooExportedOrderQuery
     */
    public function createExportedOrderQuery(): OrmFooExportedOrderQuery
    {
        return new FooExportedOrderQuery();
    }

    /**
     * @return \Orm\Zed\JellyfishBuffer\Persistence\FooExportedOrderHistoryQuery
     */
    public function createExportedOrderHistoryQuery(): FooExportedOrderHistoryQuery
    {
        return new FooExportedOrderHistoryQuery();
    }
}

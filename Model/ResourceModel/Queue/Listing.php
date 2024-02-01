<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Model\ResourceModel\Queue;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use SoftCommerce\Profile\Api\Data\ProfileInterface;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;

/**
 * @inheritDoc
 */
class Listing extends Collection
{
    private const FULLTEXT_SEARCH_FIELDS = [
        QueueInterface::SUBJECT_ENTITY_ID,
        QueueInterface::SUBJECT_TYPE_ID,
        QueueInterface::METADATA,
        QueueInterface::MESSAGE
    ];

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param SerializerInterface $serializer
     * @param RequestInterface $request
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param AdapterInterface|null $connection
     * @param AbstractDb|null $resource
     */
    public function __construct(
        SerializerInterface $serializer,
        RequestInterface $request,
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        $this->request = $request;
        parent::__construct(
            $serializer,
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
    }

    /**
     * @param string $value
     * @return $this
     */
    public function addFullTextFilter(string $value)
    {
        $whereCondition = '';
        foreach (self::FULLTEXT_SEARCH_FIELDS as $key => $field) {
            $field = 'main_table.' . $field;
            $condition = $this->_getConditionSql(
                $this->getConnection()->quoteIdentifier($field),
                ['like' => "%$value%"]
            );
            $whereCondition .= ($key === 0 ? '' : ' OR ') . $condition;
        }

        if ($whereCondition) {
            $this->getSelect()->where($whereCondition);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        if ($typeId = $this->request->getParam(ProfileInterface::TYPE_ID)) {
            $this->getSelect()->where(QueueInterface::SUBJECT_TYPE_ID . ' = ?', $typeId);
        }

        return $this;
    }
}

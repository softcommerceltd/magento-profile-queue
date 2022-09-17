<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Controller\Adminhtml\ProfileQueue;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Ui\Component\MassAction\Filter;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;
use SoftCommerce\ProfileQueue\Model\ResourceModel\Queue\Listing;
use SoftCommerce\ProfileQueue\Model\ResourceModel\Queue\ListingFactory;

/**
 * @inheritDoc
 */
class MassDelete extends AbstractMassAction
{
    /**
     * @var AdapterInterface
     */
    private AdapterInterface $connection;

    /**
     * @param ResourceConnection $resourceConnection
     * @param ListingFactory $collectionFactory
     * @param Filter $filter
     * @param Context $context
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        ListingFactory $collectionFactory,
        Filter $filter,
        Context $context
    ) {
        $this->connection = $resourceConnection->getConnection();
        parent::__construct($collectionFactory, $filter, $context);
    }

    /**
     * @inheritDoc
     */
    protected function massAction(Listing $collection): void
    {
        $ids = $collection->getAllIds();
        $result = $this->connection->delete(
            $this->connection->getTableName(QueueInterface::DB_TABLE_NAME),
            [
                QueueInterface::ENTITY_ID . ' IN (?)' => $ids
            ]
        );

        if ($result > 0) {
            $this->messageManager->addSuccessMessage(
                __(
                    'Selected profile queues have been deleted. Effected IDs: %1',
                    implode(', ', $ids)
                )
            );
        }
    }
}

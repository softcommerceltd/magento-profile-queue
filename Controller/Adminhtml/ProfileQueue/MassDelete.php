<?php
/**
 * Copyright © Byte8 Ltd (formerly Soft Commerce). All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Controller\Adminhtml\ProfileQueue;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResourceConnection;
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
     * @param ResourceConnection $resourceConnection
     * @param ListingFactory $collectionFactory
     * @param Filter $filter
     * @param Context $context
     */
    public function __construct(
        private readonly ResourceConnection $resourceConnection,
        ListingFactory $collectionFactory,
        Filter $filter,
        Context $context
    ) {
        parent::__construct($collectionFactory, $filter, $context);
    }

    /**
     * @inheritDoc
     */
    protected function massAction(Listing $collection): void
    {
        $ids = $collection->getAllIds();
        $connection = $this->resourceConnection->getConnection();

        $result = $connection->delete(
            $connection->getTableName(QueueInterface::DB_TABLE_NAME),
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

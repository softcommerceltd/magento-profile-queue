<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Model\ResourceModel\Queue;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;
use SoftCommerce\ProfileQueue\Model\Queue;
use SoftCommerce\ProfileQueue\Model\ResourceModel;

/**
 * @inheritDoc
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = QueueInterface::ENTITY_ID;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(Queue::class, ResourceModel\Queue::class);
    }
}

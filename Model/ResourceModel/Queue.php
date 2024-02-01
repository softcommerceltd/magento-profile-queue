<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Model\ResourceModel;

use SoftCommerce\Core\Model\ResourceModel\AbstractResource;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;

/**
 * @inheritDoc
 */
class Queue extends AbstractResource
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(QueueInterface::DB_TABLE_NAME, QueueInterface::ENTITY_ID);
    }
}

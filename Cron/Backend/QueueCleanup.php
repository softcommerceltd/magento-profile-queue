<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Cron\Backend;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Stdlib\DateTime\DateTime;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;

/**
 * Class QueueCleanup used to
 * clean-up profile queues.
 */
class QueueCleanup
{
    private const HISTORY_LIFETIME = 86400;

    /**
     * @param DateTime $dateTime
     * @param ResourceConnection $resource
     */
    public function __construct(
        private readonly DateTime $dateTime,
        private readonly ResourceConnection $resource
    ) {
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $connection = $this->resource->getConnection();
        $connection->delete(
            $connection->getTableName(QueueInterface::DB_TABLE_NAME),
            [
                QueueInterface::UPDATED_AT . ' < ?' => $connection->formatDate(
                    $this->dateTime->gmtTimestamp() - self::HISTORY_LIFETIME
                )
            ]
        );
    }
}

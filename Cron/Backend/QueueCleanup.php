<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Cron\Backend;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
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
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @param DateTime $dateTime
     * @param ResourceConnection $resource
     */
    public function __construct(
        DateTime $dateTime,
        ResourceConnection $resource
    ) {
        $this->dateTime = $dateTime;
        $this->connection = $resource->getConnection();
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->connection->delete(
            $this->connection->getTableName(QueueInterface::DB_TABLE_NAME),
            [
                QueueInterface::CREATED_AT . ' < ?' => $this->connection->formatDate(
                    $this->dateTime->gmtTimestamp() - self::HISTORY_LIFETIME
                )
            ]
        );
    }
}

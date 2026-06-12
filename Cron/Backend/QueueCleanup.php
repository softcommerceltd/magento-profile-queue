<?php
/**
 * Copyright © Byte8 Ltd (formerly Soft Commerce). All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Cron\Backend;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Monolog\Logger as MonoLogger;
use SoftCommerce\Core\Logger\LogProcessorInterface;
use SoftCommerce\Core\Model\Trait\BatchPurgeTrait;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;

/**
 * Class QueueCleanup
 *
 * Removes processed profile queue rows past the retention window,
 * deleting in bounded batches via BatchPurgeTrait.
 */
class QueueCleanup
{
    use BatchPurgeTrait;

    private const RETENTION_DAYS = 1;
    private const BATCH_SIZE = 5000;
    private const MAX_BATCHES = 100;
    private const LOG_TAG = 'Profile Queue Cleanup';

    /**
     * @param DateTime $dateTime
     * @param ResourceConnection $resourceConnection
     * @param LogProcessorInterface $logger
     */
    public function __construct(
        private readonly DateTime $dateTime,
        private readonly ResourceConnection $resourceConnection,
        private readonly LogProcessorInterface $logger
    ) {
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $cutoff = $this->dateTime->gmtDate(null, strtotime(sprintf('-%d days', self::RETENTION_DAYS)));

        try {
            $stats = $this->purgeByAge(
                QueueInterface::DB_TABLE_NAME,
                QueueInterface::UPDATED_AT,
                $cutoff,
                self::BATCH_SIZE,
                self::MAX_BATCHES
            );

            $this->logger->execute(
                self::LOG_TAG,
                [
                    'status' => 'completed',
                    'cutoff' => $cutoff,
                    'retention_days' => self::RETENTION_DAYS,
                    'batch_size' => self::BATCH_SIZE,
                    'max_batches' => self::MAX_BATCHES,
                    'batches_run' => $stats['batches_run'],
                    'rows_deleted' => $stats['rows_deleted'],
                    'duration_seconds' => $stats['duration_seconds'],
                    'exhausted' => $stats['exhausted']
                ],
                MonoLogger::INFO
            );
        } catch (\Exception $e) {
            $this->logger->execute(
                self::LOG_TAG . ' Error',
                [
                    'status' => 'failed',
                    'cutoff' => $cutoff,
                    'retention_days' => self::RETENTION_DAYS,
                    'error' => $e->getMessage()
                ],
                MonoLogger::ERROR
            );
        }
    }
}

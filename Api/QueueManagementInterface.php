<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Api;

use Magento\Framework\Exception\LocalizedException;

/**
 * Interface QueueManagementInterface
 * used to manage profile queue services.
 */
interface QueueManagementInterface
{
    public const BATCH_LIMIT = 50;

    /**
     * @param string $typeId
     * @param int $batchSize
     * @return array
     * @throws LocalizedException
     */
    public function getQueueBatches(string $typeId, int $batchSize = 0): array;

    /**
     * @param string $typeId
     * @param string[]|int[]|array $metadata
     * @return int
     * @throws LocalizedException
     */
    public function addToQueue(string $typeId, array $metadata): int;

    /**
     * @param string $typeId
     * @return int
     * @throws LocalizedException
     */
    public function removeFromQueue(string $typeId): int;

    /**
     * @return void
     * @throws LocalizedException
     */
    public function clearQueue(): void;
}

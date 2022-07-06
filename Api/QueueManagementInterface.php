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
     * @return array
     * @throws LocalizedException
     */
    public function getQueueBatches(): array;

    /**
     * @param int[] $variationId
     * @return int
     * @throws LocalizedException
     */
    public function addToQueue(array $variationId): int;

    /**
     * @param int[] $entityId
     * @return int
     * @throws LocalizedException
     */
    public function removeFromQueue(array $entityId): int;

    /**
     * @return void
     * @throws LocalizedException
     */
    public function clearQueue(): void;
}

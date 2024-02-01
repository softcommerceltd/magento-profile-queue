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
    /**
     * @param int|string $subjectId
     * @param string $subjectTypeId
     * @param array $metadata
     * @param array $message
     * @return int
     */
    public function addToQueue(
        int|string $subjectId,
        string $subjectTypeId,
        array $metadata = [],
        array $message = []
    ): int;

    /**
     * @param array $entityIds
     * @return int
     */
    public function removeFromQueueByEntityId(array $entityIds): int;

    /**
     * @param array|int|string $subjectEntityId
     * @param string $subjectTypeId
     * @return int
     * @throws LocalizedException
     */
    public function removeFromQueue(array|int|string $subjectEntityId, string $subjectTypeId): int;

    /**
     * @param string $subjectTypeId
     * @return int
     */
    public function removeFromQueueBySubjectTypeId(string $subjectTypeId): int;

    /**
     * @return void
     * @throws LocalizedException
     */
    public function clearQueue(): void;

    /**
     * @param array $entityIds
     * @param string $status
     * @return int
     */
    public function updateQueueStatusByEntityId(array $entityIds, string $status): int;

    /**
     * @param int|string $subjectEntityId
     * @param string $subjectTypeId
     * @param string $status
     * @return int
     */
    public function updateQueueStatusBySubjectEntityId(
        int|string $subjectEntityId,
        string $subjectTypeId,
        string $status
    ): int;

    /**
     * @param array $bindData
     * @param array|string $where
     * @return int
     */
    public function updateQueueData(array $bindData, array|string $where): int;

    /**
     * @param array $data
     * @param array $fields
     * @return int
     * @throws LocalizedException
     */
    public function saveMultipleOnDuplicate(array $data, array $fields = []): int;
}

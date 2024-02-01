<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Api\Data;

/**
 * Interface QueueInterface used to provide
 * profile queue data.
 */
interface QueueInterface
{
    public const DB_TABLE_NAME = 'softcommerce_profile_queue';

    public const ENTITY_ID = 'entity_id';
    public const SUBJECT_ENTITY_ID = 'subject_entity_id';
    public const SUBJECT_TYPE_ID = 'subject_type_id';
    public const STATUS = 'status';
    public const METADATA = 'metadata';
    public const MESSAGE = 'message';
    public const UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @return string
     */
    public function getSubjectEntityId(): string;

    /**
     * @return string
     */
    public function getSubjectTypeId(): string;

    /**
     * @return string
     */
    public function getStatus(): string;

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): static;

    /**
     * @return array
     */
    public function getMetadata(): array;

    /**
     * @return array
     */
    public function getMessage(): array;

    /**
     * @param array $data
     * @return $this
     */
    public function setMetadata(array $data): static;

    /**
     * @param array $message
     * @return $this
     */
    public function setMessage(array $message): static;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @return bool
     */
    public function isLocked(): bool;

    /**
     * @return bool
     */
    public function isPending(): bool;
}

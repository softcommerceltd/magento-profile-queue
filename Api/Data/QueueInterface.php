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
    public const TYPE_ID = 'type_id';
    public const METADATA = 'metadata';
    public const CREATED_AT = 'created_at';

    /**
     * @return null|int
     */
    public function getEntityId(): int;

    /**
     * @return string
     */
    public function getTypeId(): string;

    /**
     * @return array
     */
    public function getMetadata(): array;

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;
}

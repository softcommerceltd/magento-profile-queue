<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Model;

use Magento\Framework\Serialize\SerializerInterface;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;
use SoftCommerce\ProfileQueue\Api\QueueManagementInterface;
use SoftCommerce\ProfileQueue\Model\ResourceModel;

/**
 * @inhertidoc
 */
class QueueManagement implements QueueManagementInterface
{
    /**
     * @var ResourceModel\Queue
     */
    private $resource;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param ResourceModel\Queue $resource
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ResourceModel\Queue $resource,
        SerializerInterface $serializer
    ) {
        $this->resource = $resource;
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function getQueueBatches(): array
    {
        $result = [];
        foreach ($this->resource->getEntries() as $item) {
            try {
                $metadata = $this->serializer->unserialize($item[QueueInterface::METADATA] ?? '[]');
            } catch (\InvalidArgumentException $e) {
                $metadata = [];
            }

            foreach ($metadata as $sku) {
                $result[$sku] = $sku;
            }
        }

        return array_chunk($result, self::BATCH_LIMIT);
    }

    /**
     * @inheritDoc
     */
    public function addToQueue(array $variationId): int
    {
        try {
            $metadata = $this->serializer->serialize($variationId);
        } catch (\InvalidArgumentException $e) {
            $metadata = [];
        }

        $result = 0;
        if ($metadata) {
            $result = (int) $this->resource->insert([QueueInterface::METADATA => $metadata]);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function removeFromQueue(array $entityId): int
    {
        return (int) $this->resource->remove([QueueInterface::ENTITY_ID . ' IN (?)' => $entityId]);
    }

    /**
     * @inheritDoc
     */
    public function clearQueue(): void
    {
        $this->resource->truncateTable();
    }
}

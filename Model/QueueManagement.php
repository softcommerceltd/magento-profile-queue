<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Model;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Serialize\SerializerInterface;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;
use SoftCommerce\ProfileQueue\Api\QueueManagementInterface;
use function array_chunk;
use function array_merge;
use function array_unique;
use function current;

/**
 * @inhertidoc
 */
class QueueManagement implements QueueManagementInterface
{
    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param ResourceConnection $resourceConnection
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        SerializerInterface $serializer
    ) {
        $this->connection = $resourceConnection->getConnection();
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function getQueueBatches(string $typeId, int $batchSize = 0): array
    {
        $result = [];
        foreach ($this->getQueueData($typeId) as $item) {
            try {
                $metadata = $this->serializer->unserialize($item[QueueInterface::METADATA] ?? '[]');
            } catch (\InvalidArgumentException $e) {
                $metadata = [];
            }

            foreach ($metadata as $value) {
                $result[$value] = $value;
            }
        }

        return $batchSize
            ? array_chunk($result, $batchSize)
            : $result;
    }

    /**
     * @inheritDoc
     */
    public function addToQueue(string $typeId, array $metadata): int
    {
        $existingData = current($this->getQueueData($typeId, 1, Select::SQL_DESC)) ?: [];
        $existingId = $existingData[QueueInterface::ENTITY_ID] ?? null;

        if ($data = $existingData[QueueInterface::METADATA] ?? []) {
            try {
                $data = $this->serializer->unserialize($data);
            } catch (\InvalidArgumentException $e) {
                $data = [];
            }

            $metadata = array_merge($data, $metadata);
        }

        $metadata = array_unique($metadata);
        $result = 0;
        foreach (array_chunk($metadata, self::BATCH_LIMIT) as $metadataBatch) {
            try {
                $metadataBatch = $this->serializer->serialize($metadataBatch);
            } catch (\InvalidArgumentException $e) {
                $metadataBatch = [];
            }

            if (!$metadataBatch) {
                continue;
            }

            if (null !== $existingId) {
                $result += (int) $this->connection->update(
                    $this->connection->getTableName(QueueInterface::DB_TABLE_NAME),
                    [
                        QueueInterface::TYPE_ID => $typeId,
                        QueueInterface::METADATA => $metadataBatch
                    ],
                    [QueueInterface::ENTITY_ID . ' = ?' => $existingId]
                );
                $existingId = null;
                continue;
            }

            $result += (int) $this->connection->insert(
                $this->connection->getTableName(QueueInterface::DB_TABLE_NAME),
                [
                    QueueInterface::TYPE_ID => $typeId,
                    QueueInterface::METADATA => $metadataBatch
                ]
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function removeFromQueue(string $typeId): int
    {
        return (int) $this->connection->delete(
            $this->connection->getTableName(QueueInterface::DB_TABLE_NAME),
            [QueueInterface::TYPE_ID . ' = ?' => $typeId]
        );
    }

    /**
     * @inheritDoc
     */
    public function clearQueue(): void
    {
        $this->connection->truncateTable(
            $this->connection->getTableName(QueueInterface::DB_TABLE_NAME)
        );
    }

    /**
     * @param string $typeId
     * @param int|null $limit
     * @param string|null $sortOrder
     * @return array
     */
    private function getQueueData(string $typeId, ?int $limit = null, ?string $sortOrder = null): array
    {
        $select = $this->connection->select()
            ->from($this->connection->getTableName(QueueInterface::DB_TABLE_NAME))
            ->where(QueueInterface::TYPE_ID . ' = ?', $typeId);

        if (null !== $sortOrder) {
            $select->order(QueueInterface::ENTITY_ID . " $sortOrder");
        }

        if (null !== $limit) {
            $select->limit($limit);
        }

        return $this->connection->fetchAll($select);
    }
}

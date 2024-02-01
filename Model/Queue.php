<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Model;

use Magento\Framework\DataObject\IdentityInterface;
use SoftCommerce\Core\Model\AbstractModel;
use SoftCommerce\Core\Model\Source\StatusInterface;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;

/**
 * @inhertidoc
 */
class Queue extends AbstractModel implements QueueInterface, IdentityInterface
{
    public const CACHE_TAG = self::DB_TABLE_NAME;

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @var string
     */
    protected $_eventPrefix = self::CACHE_TAG;

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Queue::class);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritDoc
     */
    public function getEntityId(): int
    {
        return (int) $this->getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function getSubjectEntityId(): string
    {
        return $this->getData(self::SUBJECT_ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function getSubjectTypeId(): string
    {
        return (string) $this->getData(self::SUBJECT_TYPE_ID);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        return (string) $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): static
    {
        $this->setData(self::STATUS, $status);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return $this->getDataSerialized(self::METADATA);
    }

    /**
     * @inheritDoc
     */
    public function setMetadata(array $data): static
    {
        $this->setDataSerialized(self::METADATA, $data);
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function getMessage(): array
    {
        return $this->getDataSerialized(self::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage(array $message): static
    {
        $this->setDataSerialized(self::MESSAGE, $message);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function isLocked(): bool
    {
        return $this->getOrigData(self::STATUS) === StatusInterface::PROCESSING;
    }

    /**
     * @inheritDoc
     */
    public function isPending(): bool
    {
        return $this->getStatus() === StatusInterface::PENDING;
    }
}

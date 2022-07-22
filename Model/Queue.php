<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Model;

use Magento\Framework\DataObject\IdentityInterface;
use SoftCommerce\Core\Model\AbstractModel;
use SoftCommerce\ProfileQueue\Model\ResourceModel;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;

/**
 * @inhertidoc
 */
class Queue extends AbstractModel implements QueueInterface, IdentityInterface
{
    const CACHE_TAG = self::DB_TABLE_NAME;

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
    protected function _construct()
    {
        $this->_init(ResourceModel\Queue::class);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
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
    public function getTypeId(): string
    {
        return $this->getData(self::TYPE_ID);
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
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }
}

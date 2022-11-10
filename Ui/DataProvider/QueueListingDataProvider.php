<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Ui\DataProvider;

use Magento\Framework\Api\Filter;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use SoftCommerce\ProfileQueue\Model\ResourceModel\Queue\Listing;
use SoftCommerce\ProfileQueue\Model\ResourceModel\Queue\ListingFactory;

/**
 * @inheritDoc
 * Class QueueListingDataProvider used to provide
 * profile queue listing data.
 */
class QueueListingDataProvider extends AbstractDataProvider
{
    /**
     * @var PoolInterface
     */
    private PoolInterface $pool;

    /**
     * @param ListingFactory $listingFactory
     * @param PoolInterface $pool
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        ListingFactory $listingFactory,
        PoolInterface $pool,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $listingFactory->create();
        $this->pool = $pool;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        /** @var Listing $collection */
        $collection = $this->getCollection();
        $data = $collection->toArray();

        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function addFilter(Filter $filter): void
    {
        /** @var Listing $collection */
        $collection = $this->getCollection();

        if ($filter->getField() === 'fulltext') {
            $collection->addFullTextFilter(trim($filter->getValue()));
        } else {
            $collection->addFieldToFilter(
                "main_table.{$filter->getField()}",
                [$filter->getConditionType() => $filter->getValue()]
            );
        }
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    public function getMeta(): array
    {
        $meta = parent::getMeta();
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}

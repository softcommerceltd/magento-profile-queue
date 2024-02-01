<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface InventorySearchResultsInterface
 */
interface QueueSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return ExtensibleDataInterface[]
     */
    public function getItems(): array;

    /**
     * @param array $items
     * @return SearchResultsInterface
     */
    public function setItems(array $items): SearchResultsInterface;
}

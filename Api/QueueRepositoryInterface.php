<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;
use SoftCommerce\ProfileQueue\Api\Data\QueueSearchResultsInterface;

/**
 * Interface QueueRepositoryInterface used to provide
 * queue listing repository service layer.
 */
interface QueueRepositoryInterface
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResults
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults;

    /**
     * @param $entityId
     * @param $field
     * @return QueueInterface
     * @throws NoSuchEntityException
     */
    public function get($entityId, $field = null): QueueInterface;

    /**
     * @param QueueInterface $model
     * @return QueueInterface
     * @throws CouldNotSaveException
     */
    public function save(QueueInterface $model): QueueInterface;

    /**
     * @param QueueInterface $model
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(QueueInterface $model): bool;

    /**
     * @param $entityId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($entityId): bool;
}

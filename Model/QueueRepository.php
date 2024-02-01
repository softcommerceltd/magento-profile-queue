<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\ProfileQueue\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SoftCommerce\ProfileQueue\Api\Data\QueueInterface;
use SoftCommerce\ProfileQueue\Api\Data\QueueSearchResultsInterface;
use SoftCommerce\ProfileQueue\Api\Data\QueueSearchResultsInterfaceFactory;
use SoftCommerce\ProfileQueue\Api\QueueRepositoryInterface;

/**
 * @inheritDoc
 */
class QueueRepository implements QueueRepositoryInterface
{
    /**
     * @var QueueFactory
     */
    private QueueFactory $modelFactory;

    /**
     * @var ResourceModel\Queue
     */
    private ResourceModel\Queue $resource;

    /**
     * @var ResourceModel\Queue\CollectionFactory
     */
    private ResourceModel\Queue\CollectionFactory $collectionFactory;

    /**
     * @var QueueSearchResultsInterfaceFactory
     */
    private QueueSearchResultsInterfaceFactory $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @param QueueFactory $modelFactory
     * @param ResourceModel\Queue $resource
     * @param ResourceModel\Queue\CollectionFactory $collectionFactory
     * @param QueueSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        QueueFactory $modelFactory,
        ResourceModel\Queue $resource,
        ResourceModel\Queue\CollectionFactory $collectionFactory,
        QueueSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResults
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var QueueSearchResultsInterface|SearchResults $searchResults */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function get($entityId, $field = null): QueueInterface
    {
        /** @var QueueInterface $model */
        $model = $this->modelFactory->create();
        $this->resource->load($model, $entityId, $field);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('The entity with ID "%1" doesn\'t exist.', $entityId));
        }
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function save(QueueInterface $model): QueueInterface
    {
        try {
            $this->resource->save($model);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function delete(QueueInterface $model): bool
    {
        try {
            $this->resource->delete($model);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($entityId): bool
    {
        return $this->delete($this->get($entityId));
    }
}

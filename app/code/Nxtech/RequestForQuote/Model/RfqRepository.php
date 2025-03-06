<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\RequestForQuote\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nxtech\RequestForQuote\Api\Data\RfqInterface;
use Nxtech\RequestForQuote\Api\Data\RfqInterfaceFactory;
use Nxtech\RequestForQuote\Api\Data\RfqSearchResultsInterfaceFactory;
use Nxtech\RequestForQuote\Api\RfqRepositoryInterface;
use Nxtech\RequestForQuote\Model\ResourceModel\Rfq as ResourceRfq;
use Nxtech\RequestForQuote\Model\ResourceModel\Rfq\CollectionFactory as RfqCollectionFactory;

class RfqRepository implements RfqRepositoryInterface
{

    /**
     * @var RfqInterfaceFactory
     */
    protected $rfqFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Rfq
     */
    protected $searchResultsFactory;

    /**
     * @var RfqCollectionFactory
     */
    protected $rfqCollectionFactory;

    /**
     * @var ResourceRfq
     */
    protected $resource;


    /**
     * @param ResourceRfq $resource
     * @param RfqInterfaceFactory $rfqFactory
     * @param RfqCollectionFactory $rfqCollectionFactory
     * @param RfqSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceRfq $resource,
        RfqInterfaceFactory $rfqFactory,
        RfqCollectionFactory $rfqCollectionFactory,
        RfqSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->rfqFactory = $rfqFactory;
        $this->rfqCollectionFactory = $rfqCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(RfqInterface $rfq)
    {
        try {
            $this->resource->save($rfq);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the rfq: %1',
                $exception->getMessage()
            ));
        }
        return $rfq;
    }

    /**
     * @inheritDoc
     */
    public function get($rfqId)
    {
        $rfq = $this->rfqFactory->create();
        $this->resource->load($rfq, $rfqId);
        if (!$rfq->getId()) {
            throw new NoSuchEntityException(__('rfq with id "%1" does not exist.', $rfqId));
        }
        return $rfq;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->rfqCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(RfqInterface $rfq)
    {
        try {
            $rfqModel = $this->rfqFactory->create();
            $this->resource->load($rfqModel, $rfq->getRfqId());
            $this->resource->delete($rfqModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the rfq: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($rfqId)
    {
        return $this->delete($this->get($rfqId));
    }
}


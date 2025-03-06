<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nxtech\TicketManager\Api\Data\TicketManagerInterface;
use Nxtech\TicketManager\Api\Data\TicketManagerInterfaceFactory;
use Nxtech\TicketManager\Api\Data\TicketManagerSearchResultsInterfaceFactory;
use Nxtech\TicketManager\Api\TicketManagerRepositoryInterface;
use Nxtech\TicketManager\Model\ResourceModel\TicketManager as ResourceTicketManager;
use Nxtech\TicketManager\Model\ResourceModel\TicketManager\CollectionFactory as TicketManagerCollectionFactory;

class TicketManagerRepository implements TicketManagerRepositoryInterface
{

    /**
     * @var ResourceTicketManager
     */
    protected $resource;

    /**
     * @var TicketManagerCollectionFactory
     */
    protected $ticketManagerCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var TicketManagerInterfaceFactory
     */
    protected $ticketManagerFactory;

    /**
     * @var TicketManager
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceTicketManager $resource
     * @param TicketManagerInterfaceFactory $ticketManagerFactory
     * @param TicketManagerCollectionFactory $ticketManagerCollectionFactory
     * @param TicketManagerSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceTicketManager $resource,
        TicketManagerInterfaceFactory $ticketManagerFactory,
        TicketManagerCollectionFactory $ticketManagerCollectionFactory,
        TicketManagerSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->ticketManagerFactory = $ticketManagerFactory;
        $this->ticketManagerCollectionFactory = $ticketManagerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(TicketManagerInterface $ticketManager)
    {
        try {
            $this->resource->save($ticketManager);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the ticketManager: %1',
                $exception->getMessage()
            ));
        }
        return $ticketManager;
    }

    /**
     * @inheritDoc
     */
    public function get($ticketManagerId)
    {
        $ticketManager = $this->ticketManagerFactory->create();
        $this->resource->load($ticketManager, $ticketManagerId);
        if (!$ticketManager->getId() && $ticketManager->getId() != 0) {
            throw new NoSuchEntityException(__('TicketManager with id "%1" does not exist.', $ticketManagerId));
        }
        return $ticketManager;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->ticketManagerCollectionFactory->create();

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
    public function delete(TicketManagerInterface $ticketManager)
    {
        try {
            $ticketManagerModel = $this->ticketManagerFactory->create();
            $this->resource->load($ticketManagerModel, $ticketManager->getId());
            $this->resource->delete($ticketManagerModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the TicketManager: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($ticketManagerId)
    {
        return $this->delete($this->get($ticketManagerId));
    }
}


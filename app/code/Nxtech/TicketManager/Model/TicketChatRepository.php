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
use Nxtech\TicketManager\Api\Data\TicketChatInterface;
use Nxtech\TicketManager\Api\Data\TicketChatInterfaceFactory;
use Nxtech\TicketManager\Api\Data\TicketChatSearchResultsInterfaceFactory;
use Nxtech\TicketManager\Api\TicketChatRepositoryInterface;
use Nxtech\TicketManager\Model\ResourceModel\TicketChat as ResourceTicketChat;
use Nxtech\TicketManager\Model\ResourceModel\TicketChat\CollectionFactory as TicketChatCollectionFactory;

class TicketChatRepository implements TicketChatRepositoryInterface
{

    /**
     * @var TicketChatCollectionFactory
     */
    protected $ticketChatCollectionFactory;

    /**
     * @var ResourceTicketChat
     */
    protected $resource;

    /**
     * @var TicketChatInterfaceFactory
     */
    protected $ticketChatFactory;

    /**
     * @var TicketChat
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * @param ResourceTicketChat $resource
     * @param TicketChatInterfaceFactory $ticketChatFactory
     * @param TicketChatCollectionFactory $ticketChatCollectionFactory
     * @param TicketChatSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceTicketChat $resource,
        TicketChatInterfaceFactory $ticketChatFactory,
        TicketChatCollectionFactory $ticketChatCollectionFactory,
        TicketChatSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->ticketChatFactory = $ticketChatFactory;
        $this->ticketChatCollectionFactory = $ticketChatCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(TicketChatInterface $ticketChat)
    {
        try {
            $this->resource->save($ticketChat);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the ticketChat: %1',
                $exception->getMessage()
            ));
        }
        return $ticketChat;
    }

    /**
     * @inheritDoc
     */
    public function get($ticketChatId)
    {
        $ticketChat = $this->ticketChatFactory->create();
        $this->resource->load($ticketChat, $ticketChatId);
        if (!$ticketChat->getId()) {
            throw new NoSuchEntityException(__('TicketChat with id "%1" does not exist.', $ticketChatId));
        }
        return $ticketChat;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->ticketChatCollectionFactory->create();
        
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
    public function delete(TicketChatInterface $ticketChat)
    {
        try {
            $ticketChatModel = $this->ticketChatFactory->create();
            $this->resource->load($ticketChatModel, $ticketChat->getTicketchatId());
            $this->resource->delete($ticketChatModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the TicketChat: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($ticketChatId)
    {
        return $this->delete($this->get($ticketChatId));
    }
}


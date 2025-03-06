<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TicketChatRepositoryInterface
{

    /**
     * Save TicketChat
     * @param \Nxtech\TicketManager\Api\Data\TicketChatInterface $ticketChat
     * @return \Nxtech\TicketManager\Api\Data\TicketChatInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Nxtech\TicketManager\Api\Data\TicketChatInterface $ticketChat
    );

    /**
     * Retrieve TicketChat
     * @param string $ticketchatId
     * @return \Nxtech\TicketManager\Api\Data\TicketChatInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($ticketchatId);

    /**
     * Retrieve TicketChat matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Nxtech\TicketManager\Api\Data\TicketChatSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete TicketChat
     * @param \Nxtech\TicketManager\Api\Data\TicketChatInterface $ticketChat
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Nxtech\TicketManager\Api\Data\TicketChatInterface $ticketChat
    );

    /**
     * Delete TicketChat by ID
     * @param string $ticketchatId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($ticketchatId);
}


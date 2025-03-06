<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TicketManagerRepositoryInterface
{

    /**
     * Save TicketManager
     * @param \Nxtech\TicketManager\Api\Data\TicketManagerInterface $ticketManager
     * @return \Nxtech\TicketManager\Api\Data\TicketManagerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Nxtech\TicketManager\Api\Data\TicketManagerInterface $ticketManager
    );

    /**
     * Retrieve TicketManager
     * @param string $ticketmanagerId
     * @return \Nxtech\TicketManager\Api\Data\TicketManagerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($ticketmanagerId);

    /**
     * Retrieve TicketManager matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Nxtech\TicketManager\Api\Data\TicketManagerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete TicketManager
     * @param \Nxtech\TicketManager\Api\Data\TicketManagerInterface $ticketManager
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Nxtech\TicketManager\Api\Data\TicketManagerInterface $ticketManager
    );

    /**
     * Delete TicketManager by ID
     * @param string $ticketmanagerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($ticketmanagerId);
}


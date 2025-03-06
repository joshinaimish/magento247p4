<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Api\Data;

interface TicketChatSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get TicketChat list.
     * @return \Nxtech\TicketManager\Api\Data\TicketChatInterface[]
     */
    public function getItems();

    /**
     * Set ticketId list.
     * @param \Nxtech\TicketManager\Api\Data\TicketChatInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}


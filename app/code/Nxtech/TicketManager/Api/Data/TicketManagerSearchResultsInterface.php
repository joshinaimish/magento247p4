<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Api\Data;

interface TicketManagerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get TicketManager list.
     * @return \Nxtech\TicketManager\Api\Data\TicketManagerInterface[]
     */
    public function getItems();

    /**
     * Set ticket_type list.
     * @param \Nxtech\TicketManager\Api\Data\TicketManagerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}


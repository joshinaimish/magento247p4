<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\RequestForQuote\Api\Data;

interface RfqSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get rfq list.
     * @return \Nxtech\RequestForQuote\Api\Data\RfqInterface[]
     */
    public function getItems();

    /**
     * Set name list.
     * @param \Nxtech\RequestForQuote\Api\Data\RfqInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}


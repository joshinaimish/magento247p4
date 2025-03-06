<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\GraphqlCRUD\Api\Data;

interface StudentSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Student list.
     * @return \Nxtech\GraphqlCRUD\Api\Data\StudentInterface[]
     */
    public function getItems();

    /**
     * Set name list.
     * @param \Nxtech\GraphqlCRUD\Api\Data\StudentInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}


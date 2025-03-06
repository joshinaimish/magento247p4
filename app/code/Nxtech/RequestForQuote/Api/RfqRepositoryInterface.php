<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\RequestForQuote\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface RfqRepositoryInterface
{

    /**
     * Save rfq
     * @param \Nxtech\RequestForQuote\Api\Data\RfqInterface $rfq
     * @return \Nxtech\RequestForQuote\Api\Data\RfqInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Nxtech\RequestForQuote\Api\Data\RfqInterface $rfq
    );

    /**
     * Retrieve rfq
     * @param string $rfqId
     * @return \Nxtech\RequestForQuote\Api\Data\RfqInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($rfqId);

    /**
     * Retrieve rfq matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Nxtech\RequestForQuote\Api\Data\RfqSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete rfq
     * @param \Nxtech\RequestForQuote\Api\Data\RfqInterface $rfq
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Nxtech\RequestForQuote\Api\Data\RfqInterface $rfq
    );

    /**
     * Delete rfq by ID
     * @param string $rfqId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($rfqId);
}


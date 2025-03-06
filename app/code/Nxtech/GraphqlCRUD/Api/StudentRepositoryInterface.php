<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\GraphqlCRUD\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface StudentRepositoryInterface
{

    /**
     * Save Student
     * @param \Nxtech\GraphqlCRUD\Api\Data\StudentInterface $student
     * @return \Nxtech\GraphqlCRUD\Api\Data\StudentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Nxtech\GraphqlCRUD\Api\Data\StudentInterface $student
    );

    /**
     * Retrieve Student
     * @param string $id
     * @return \Nxtech\GraphqlCRUD\Api\Data\StudentInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($id);

    /**
     * Retrieve Student matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Nxtech\GraphqlCRUD\Api\Data\StudentSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Student
     * @param \Nxtech\GraphqlCRUD\Api\Data\StudentInterface $student
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Nxtech\GraphqlCRUD\Api\Data\StudentInterface $student
    );

    /**
     * Delete Student by ID
     * @param string $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}


<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\GraphqlCRUD\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Nxtech\GraphqlCRUD\Api\Data\StudentInterface;
use Nxtech\GraphqlCRUD\Api\Data\StudentInterfaceFactory;
use Nxtech\GraphqlCRUD\Api\Data\StudentSearchResultsInterfaceFactory;
use Nxtech\GraphqlCRUD\Api\StudentRepositoryInterface;
use Nxtech\GraphqlCRUD\Model\ResourceModel\Student as ResourceStudent;
use Nxtech\GraphqlCRUD\Model\ResourceModel\Student\CollectionFactory as StudentCollectionFactory;

class StudentRepository implements StudentRepositoryInterface
{

    /**
     * @var StudentCollectionFactory
     */
    protected $studentCollectionFactory;

    /**
     * @var ResourceStudent
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Student
     */
    protected $searchResultsFactory;

    /**
     * @var StudentInterfaceFactory
     */
    protected $studentFactory;


    /**
     * @param ResourceStudent $resource
     * @param StudentInterfaceFactory $studentFactory
     * @param StudentCollectionFactory $studentCollectionFactory
     * @param StudentSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceStudent $resource,
        StudentInterfaceFactory $studentFactory,
        StudentCollectionFactory $studentCollectionFactory,
        StudentSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->studentFactory = $studentFactory;
        $this->studentCollectionFactory = $studentCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(StudentInterface $student)
    {
        try {
            $this->resource->save($student);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the student: %1',
                $exception->getMessage()
            ));
        }
        return $student;
    }

    /**
     * @inheritDoc
     */
    public function get($Id)
    {
        $student = $this->studentFactory->create();
        $this->resource->load($student, $Id);
        if (!$student->getId()) {
            throw new NoSuchEntityException(__('Student with id "%1" does not exist.', $Id));
        }
        return $student;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->studentCollectionFactory->create();

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
    public function delete(StudentInterface $student)
    {
        try {
            $studentModel = $this->studentFactory->create();
            $this->resource->load($studentModel, $student->getId());
            $this->resource->delete($studentModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Student: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($id)
    {
        return $this->delete($this->get($id));
    }
}


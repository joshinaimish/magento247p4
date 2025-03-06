<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\Blog\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Nxtech\Blog\Model\ResourceModel\Blog\CollectionFactory;
use Nxtech\Blog\Api\BlogRepositoryInterface;
use Nxtech\Blog\Api\Data\BlogInterface;

class MassDelete extends Action
{
    /**
     * @var Webkul\BlogManager\Model\ResourceModel\Blog\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    protected $blogRepository;

    protected $blog;
    /**
     * Dependency Initilization
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        BlogRepositoryInterface $blogRepository,
        BlogInterface $blog,
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->blogRepository = $blogRepository;
        $this->blog = $blog;
        parent::__construct($context);
    }
    /**
     * Provides content
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $count = 0;
            foreach ($collection as $model) {
                //$model->delete();
                $this->blogRepository->delete($model);
                $count++;
            }
            $this->messageManager->addSuccess(__('A total of %1 blog(s) have been deleted.', $count));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
    /**
     * Check Autherization
     *
     * @return boolean
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Nxtech_Blog::delete');
    }
}
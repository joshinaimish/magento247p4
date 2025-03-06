<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\Blog\Controller\Adminhtml\Blog;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;
use Nxtech\Blog\Model\ImageUploader;
use Nxtech\Blog\Api\BlogRepositoryInterface;
use Nxtech\Blog\Api\Data\BlogInterface;
use Magento\Framework\Api\DataObjectHelper;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    protected $imageUploaderModel;

    protected $blogRepository;

    protected $blog;

    protected $blogProductRepository;

    protected $blogProduct;

    protected $blogProductFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private PageFactory $resultPageFactory,
        ImageUploader $imageUploaderModel,
        BlogRepositoryInterface $blogRepository,
        BlogInterface $blog,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->blogRepository = $blogRepository;
        $this->blog = $blog;
        $this->imageUploaderModel = $imageUploaderModel;
        $this->dataPersistor = $dataPersistor;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();


        if ($data) {
            $id = $this->getRequest()->getParam('id');

            if ($id == "") {
                $model = $this->blog;
                $data['created_at'] = date('y-m-d h:i:s');
            } else {
                $model = $this->blogRepository->get($id);
                if (!$model->getId() && $id) {
                    $this->messageManager->addErrorMessage(__('This Blog no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
                $data['updated_at'] = date('y-m-d h:i:s');
            }
            //echo $data['nx_products'];exit;

            //$page = implode(",", $data['category']);
            //$data['category'] = $page;

            $model->setData($data);
            $model = $this->imageData($model, $data);

            /* $this->dataObjectHelper->populateWithArray(
               $model,
               $data,
               'Nxtech\Blog\Api\Data\BlogInterface'
           );  */

            try {
                $model->save();

                $blogId = $model->getId();

                $this->messageManager->addSuccessMessage(__('You saved the Blog.'));
                $this->dataPersistor->clear('nxtech_blog_blog');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Blog.'));
            }

            $this->dataPersistor->set('nxtech_blog_blog', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $model
     * @param $data
     * @return mixed
     */
    public function imageData($model, $data)
    {
        if ($model->getId()) {
            //$pageData = $this->_objectManager->create(\Nxtech\Blog\Model\Blog::class)->load($model->getId());
            $pageData = $this->blogRepository->get($model->getId());

            if (isset($data['blog_image'][0]['name'])) {
                $imageName1 = $pageData->getBlogImage();
                $imageName2 = $data['blog_image'][0]['name'];
                if ($imageName1 != $imageName2) {
                    $imageUrl = $data['blog_image'][0]['url'];
                    $imageName = $data['blog_image'][0]['name'];
                    $data['blog_image'] = $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
                } else {
                    $data['blog_image'] = $data['blog_image'][0]['name'];
                }
            } else {
                $data['blog_image'] = '';
            }
        } else {
            if (isset($data['blog_image'][0]['name'])) {
                $imageUrl = $data['blog_image'][0]['url'];
                $imageName = $data['blog_image'][0]['name'];
                $data['blog_image'] = $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
            }
        }
        $model->setData($data);
        return $model;
    }
}


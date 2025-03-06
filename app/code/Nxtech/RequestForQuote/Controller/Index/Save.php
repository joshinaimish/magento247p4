<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\RequestForQuote\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Nxtech\RequestForQuote\Api\Data\RfqInterface;
use Nxtech\RequestForQuote\Model\Common;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;

class Save extends Action
{
    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        protected PageFactory $resultPageFactory,
        protected JsonFactory $resultJsonFactory,
        protected StoreManagerInterface $storeManager,
        protected RfqInterface $rfqInterface,
        protected Common $common,
        protected UploaderFactory $uploaderFactory,
        protected AdapterFactory $adapterFactory,
        protected Filesystem $filesystem,
    ) {
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $resultData = $this->resultJsonFactory->create();
            $model = $this->rfqInterface;
            $data = $this->getRequest()->getPostValue();
            $files = $this->getRequest()->getFiles('file_attachment');

            if ($data) {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'requestquote/';
                $filename = str_replace(" ", "_", $files["name"]);
                $response = [];

                if (isset($files) && !empty($files["name"])) {
                    try {
                        $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'file_attachment']);
                        $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'png']); // ['jpg', 'jpeg','png']
                        $imageAdapter = $this->adapterFactory->create();
                        $uploaderFactory->addValidateCallback('custom_image_upload', $imageAdapter, 'validateUploadFile');
                        $uploaderFactory->setAllowRenameFiles(true);
                        $uploaderFactory->setFilesDispersion(false);
                        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                        $destinationPath = $mediaDirectory->getAbsolutePath('requestquote');
                        $result = $uploaderFactory->save($destinationPath, $filename);
                        $newFileName = $result['file'];

                        if (!$result) {
                            throw new LocalizedException(
                                __('File cannot be saved to path: $1', $destinationPath)
                            );
                        }
                        $data['file_attachment'] = $newFileName;

                    } catch (\Exception $e) {
                        $this->messageManager->addErrorMessage($e->getMessage() . ' Please upload JPG,JPEG,PNG file type only');
                        $response['err'] = 400;
                        $response['err_message'] = 'Please upload JPG,JPEG,PNG file type only';
                        return $resultData->setData(['data' => $response]);
                    }
                }



                $data['created_at'] = date('Y-m-d');
                $data['updated_at'] = date('Y-m-d');
                $model->setData($data);


                try {
                    $model->save();
                    // Send email: Working functionality

                    $this->common->sendEmail($data);
                    $response['code'] = 200;
                    $this->messageManager->addSuccess(__("You saved the Rfq Successfully"));
                    $response['message'] = __('You saved the Rfq.');

                } catch (\Exception $e) {
                    $response['code'] = 400;
                    $response['message'] = __($e->getMessage());
                    $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Rfq.'));
                }
                return $resultData->setData(['data' => $response]);
            }
        }
    }
}


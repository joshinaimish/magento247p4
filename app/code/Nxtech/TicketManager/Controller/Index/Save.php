<?php
namespace Nxtech\TicketManager\Controller\Index;

use Magento\Framework\Exception\LocalizedException;
use Nxtech\TicketManager\Model\ImageUploader;
use Nxtech\TicketManager\Api\TicketManagerRepositoryInterface;
use Nxtech\TicketManager\Api\Data\TicketManagerInterface;
use Nxtech\TicketManager\Api\TicketChatRepositoryInterface;
use Nxtech\TicketManager\Api\Data\TicketChatInterface;
use Magento\Framework\Api\DataObjectHelper;

use Magento\Framework\Filesystem\Io\File;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Magento\Framework\App\Action\Action
{
    const USER_TYPE = 'customer';
    const STATUS = 1;
    const ATTACHMENT_FILE = 'wysiwyg/ticketmanager/';
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        protected ImageUploader $imageUploaderModel,
        protected TicketManagerRepositoryInterface $ticketManagerRepository,
        protected TicketManagerInterface $ticketManager,
        protected TicketChatRepositoryInterface $ticketChatRepository,
        protected TicketChatInterface $ticketChat,
        protected \Magento\Customer\Model\Session $customerSession,
        protected DataObjectHelper $dataObjectHelper,
        protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        protected UploaderFactory $uploaderFactory,
        protected \Magento\Framework\Filesystem $fileSystem,
        protected File $fileIo
    ) {

        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $customer = $this->getCustomerDetail();

        if ($data) {
            $id = (int) $this->getRequest()->getParam('ticket_id');
            $file = $_FILES;
            if (empty($id)) {

                $model = $this->ticketManager;
                $data['status'] = self::STATUS;
                //$data['created_at'] = date('y-m-d h:i:s');
            } else {
                $model = $this->ticketManagerRepository->get($id);

                if (!$model->getId() && $id) {
                    $this->messageManager->addErrorMessage(__('This Ticket no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
                //$data['updated_at'] = date('y-m-d h:i:s');
            }
            $data['user_id'] = $customer->getEntityId();
            $data['user_type'] = self::USER_TYPE;

            $this->dataObjectHelper->populateWithArray(
                $model,
                $data,
                'Nxtech/TicketManager/Api/Data/TicketManagerInterface'
            );
            try {
                $this->ticketManagerRepository->save($model);
                $ticketManagerId = $model->getId();

                $this->saveTicketChat($data, $ticketManagerId, $file);
                $this->messageManager->addSuccessMessage(__('You saved the Ticketmanager.'));
                $this->dataPersistor->clear('ticketmanager');
                return $resultRedirect->setPath('ticket/index/edit', ['id' => $ticketManagerId]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Ticketmanager.'));
            }
            $this->dataPersistor->set('ticketmanager', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $ticketManagerId]);
        }
        return $resultRedirect->setPath('*/*/');
    }
    public function saveTicketChat($data, $ticketManagerId, $file)
    {

        $chatData = [];
        $ticketChatModel = $this->ticketChat;
        $chatData['ticket_id'] = $ticketManagerId;
        $chatData['user_id'] = $data['user_id'];
        $chatData['user_type'] = $data['user_type'];
        $chatData['ticket_description'] = $data['ticket_description'];
        $chatData['created_at'] = date('y-m-d h:i:s');
        $chatData['updated_at'] = date('y-m-d h:i:s');

        /* if (isset($data['attachment'])) {
            $imageUrl = $data['attachment'][0]['url'];
            $imageName = $data['attachment'][0]['name'];
            $chatData['attachment'] = $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
        } */

        try {
            if (isset($file['attachment']) && $file['attachment']['name'] != '') {
                // Get the image name and temporary file path
                $imageName = $file['attachment']['name'];
                $tempPath = $file['attachment']['tmp_name'];

                // Call the image uploader method to save the file and get the new file path
                $uploadedFilePath = $this->saveMediaImage($imageName, $tempPath);

                // Save the uploaded file path in the database or model
                //$ticketChatModel->setData('attachment', $uploadedFilePath);

                $chatData['attachment'] = $uploadedFilePath;
            }
        } catch (\Exception $e) {
            // Handle exceptions or log errors if needed
            $this->messageManager->addErrorMessage(__('File upload failed: ' . $e->getMessage()));
        }



        $this->dataObjectHelper->populateWithArray(
            $ticketChatModel,
            $chatData,
            'Nxtech/TicketManager/Api/Data/TicketChatInterface'
        );
        /* print_r($chatData);
        exit; */
        try {
            //$ticketChatModel->save($ticketChatModel);
            $this->ticketChatRepository->save($ticketChatModel);
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Ticket Chat.'));
        }
        return true;
    }

    public function saveMediaImage($imageName, $tempPath)
    {
        $mediaDirectory = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        $targetPath = self::ATTACHMENT_FILE;
        $destinationPath = $mediaDirectory->getAbsolutePath($targetPath);

        // Ensure the directory exists
        $this->fileIo->checkAndCreateFolder($destinationPath);

        // Move the uploaded file to the media directory
        $newFilePath = $destinationPath . $imageName;
        if (move_uploaded_file($tempPath, $newFilePath)) {
            // Return the relative path to be saved in the database
            //return $targetPath . $imageName;
            return $imageName;
        }

        throw new \Exception(__('File upload failed.'));
    }

    public function getCustomerDetail()
    {
        return $this->customerSession->getCustomer();
    }
}

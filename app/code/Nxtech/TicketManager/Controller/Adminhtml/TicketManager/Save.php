<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\TicketManager\Controller\Adminhtml\TicketManager;

use Magento\Framework\Exception\LocalizedException;
use Nxtech\TicketManager\Model\ImageUploader;
use Nxtech\TicketManager\Api\TicketManagerRepositoryInterface;
use Nxtech\TicketManager\Api\Data\TicketManagerInterface;
use Nxtech\TicketManager\Api\TicketChatRepositoryInterface;
use Nxtech\TicketManager\Api\Data\TicketChatInterface;
use Magento\Framework\Api\DataObjectHelper;

class Save extends \Magento\Backend\App\Action
{
    const USER_TYPE = 'admin';

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected ImageUploader $imageUploaderModel,
        protected TicketManagerRepositoryInterface $ticketManagerRepository,
        protected TicketManagerInterface $ticketManager,
        protected TicketChatRepositoryInterface $ticketChatRepository,
        protected TicketChatInterface $ticketChat,
        protected \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        protected \Magento\Backend\Model\Auth\Session $authSession,
        protected DataObjectHelper $dataObjectHelper
    ) {
        $this->dataPersistor = $dataPersistor;
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
        $adminUser = $this->getCurrentUser();
        /* echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit; */
        if ($data) {
            $id = $this->getRequest()->getParam('id');

            if ($id == "") {
                $model = $this->ticketManager;
                $data['user_id'] = $adminUser->getUserId();
                $data['user_type'] = self::USER_TYPE;
                //$data['created_at'] = date('y-m-d h:i:s');
            } else {
                $model = $this->ticketManagerRepository->get($id);
                if (!$model->getId() && $id) {
                    $this->messageManager->addErrorMessage(__('This Ticket no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
                //$data['updated_at'] = date('y-m-d h:i:s');
            }

            $this->dataObjectHelper->populateWithArray(
                $model,
                $data,
                'Nxtech\TicketManager\Api\Data\TicketManagerInterface'
            );
            //$model->setData($data);

            try {
                //$model->save();
                $this->ticketManagerRepository->save($model);
                $ticketManagerId = $model->getId();
                $this->saveTicketChat($data, $ticketManagerId);
                $this->messageManager->addSuccessMessage(__('You saved the Ticketmanager.'));
                $this->dataPersistor->clear('ticketmanager');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Ticketmanager.'));
            }

            $this->dataPersistor->set('ticketmanager', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function saveTicketChat($data, $ticketManagerId)
    {
        $chatData = [];
        $ticketChatModel = $this->ticketChat;
        $chatData['ticket_id'] = $ticketManagerId;
        $chatData['user_id'] = $data['user_id'];
        $chatData['user_type'] = $data['user_type'];
        $chatData['ticket_description'] = $data['ticket_description'];
        if (isset($data['attachment'])) {
            $chatData['attachment'] = $data['attachment'];
        }
        $chatData['created_at'] = date('y-m-d h:i:s');
        $chatData['updated_at'] = date('y-m-d h:i:s');

        if (isset($data['attachment'])) {
            $chatData['attachment'] = $this->imageData($ticketChatModel, $chatData);
        }

        $this->dataObjectHelper->populateWithArray(
            $ticketChatModel,
            $chatData,
            'Nxtech/TicketManager/Api/Data/TicketChatInterface'
        );

        try {
            //$ticketChatModel->save($ticketChatModel);
            $this->ticketChatRepository->save($ticketChatModel);
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Ticket Chat.'));
        }
        return true;
    }
    /**
     * @param $ticketChatModel
     * @param $chatData
     * @return mixed
     */
    public function imageData($ticketChatModel, $chatData)
    {
        if (isset($chatData['attachment'][0]['name'])) {
            $imageUrl = $chatData['attachment'][0]['url'];
            $imageName = $chatData['attachment'][0]['name'];
            return $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
        }
        return;
    }

    public function getCurrentUser()
    {
        return $this->authSession->getUser();
    }
}


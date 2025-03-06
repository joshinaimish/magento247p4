<?php
namespace Nxtech\TicketManager\Block;
use \Nxtech\TicketManager\Model\Source\Priority;
use \Nxtech\TicketManager\Model\Source\Category;
use \Nxtech\TicketManager\Model\Source\Status;
use Magento\Framework\Data\Form\FormKey;
use Magento\User\Model\UserFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\Session;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Customer\Api\AccountManagementInterface;

class TicketManager extends \Magento\Customer\Block\Account\Dashboard
{
    const TICKETVIEW = 'ticket/index/index';
    const TICKETADD = 'ticket/index/add';
    const TICKETEDIT = 'ticket/index/edit/id/';
    const TICKETSAVE = 'ticket/index/save';
    const USER_TYPE = 'customer';
    const ATTACHMENT_PATH = 'wysiwyg/ticketmanager/';

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Session $customerSession,
        SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected Priority $priority,
        protected Category $category,
        protected Status $status,
        protected \Nxtech\TicketManager\Model\TicketManager $ticketManagerCollection,
        protected \Nxtech\TicketManager\Model\ResourceModel\TicketChat\Collection $ticketChatCollection,
        //protected \Magento\Customer\Model\Session $customerSession,
        protected FormKey $formKey,
        protected UserFactory $userFactory,
        array $data = []
    ) {
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        //$this->pageConfig->getTitle()->set(__('Ticket Detail'));
        if ($this->getTicketCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'nxtech.ticket.history.pager'
            )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
                ->setShowPerPage(true)->setCollection(
                    $this->getTicketCollection()
                );
            $this->setChild('pager', $pager);
            $this->getTicketCollection()->load();
        }
        return $this;
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    public function getTicketCollection()
    {
        // Load and return the ticket collection
        /* $collection = $this->ticketCollectionFactory->create();
        $collection->addFieldToSelect('*'); // Specify fields to select
        return $collection; */
        $customerId = (int) $this->getCustomerId();
        $type = self::USER_TYPE;
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $collection = $this->ticketManagerCollection->getCollection();
        $collection->addFieldToFilter('user_id', $customerId);
        $collection->addFieldToFilter('user_type', $type);
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }
    public function getTicket()
    {
        $id = (int) $this->getRequest()->getParam('id');
        //$customer = $this->getCustomer();
        //echo $customerId = (int) $customer->getEntityId();
        $customerId = (int) $this->getCustomerId();
        $type = self::USER_TYPE;
        $collection = $this->ticketManagerCollection->getCollection();
        $collection->addFieldToFilter('id', $id);
        $collection->addFieldToFilter('user_id', $customerId);
        $collection->addFieldToFilter('user_type', ['eq' => $type]);
        return $collection->getFirstItem();
        ;
    }
    public function getTicketHistory($id)
    {
        $customer = $this->getCustomer();
        //$customerId = (int) $customer->getEntityId();
        $customerId = (int) $this->getCustomerId();
        //echo "ID:" . $customerId = $this->getCustomer();
        //$type = self::USER_TYPE;
        $collection = $this->ticketChatCollection
            ->addFieldToFilter('ticket_id', $id)
            ->addFieldToFilter('user_id', $customerId);
        //->addFieldToFilter('user_type', ['eq' => $type]);
        return $collection;
    }
    public function getAdminName($adminId)
    {
        $user = $this->userFactory->create()->load($adminId);
        return $user->getName();
    }
    public function getCustomerName($customerId)
    {
        try {
            $customer = $this->customerRepository->getById($customerId);
            if ($customer->getId()) {
                return $customer->getFirstname() . ' ' . $customer->getLastname();
            }
        } catch (NoSuchEntityException $e) {
            //$this->logger->error('Customer does not exist with ID: ' . $customerId);
            return false;
        } catch (\Exception $e) {
            //$this->logger->error('An unexpected error occurred: ' . $e->getMessage());
            return false;
        }
    }
    public function getAttachmentFile($file)
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . self::ATTACHMENT_PATH . $file;
    }
    public function getCustomer()
    {
        return $this->customerSession->getCustomer();
    }
    public function getCustomerId()
    {
        return $this->customerSession->getCustomerId();
    }
    public function getViewUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl() . self::TICKETVIEW;
    }
    public function getAddUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl() . self::TICKETADD;
    }
    public function getEditUrl($id)
    {
        return $this->storeManager->getStore()->getBaseUrl() . self::TICKETEDIT . $id;
    }
    public function getFormAction()
    {
        return $this->storeManager->getStore()->getBaseUrl() . self::TICKETSAVE;
    }
    public function getPriority()
    {
        return $this->priority->toOptionArray();
    }
    public function getCategory()
    {
        return $this->category->toOptionArray();
    }
    public function getCategoryText($category)
    {
        return $this->category->getOptionText($category);
    }
    public function getPriorityText($priority)
    {
        return $this->priority->getOptionText($priority);
    }
    public function getStatusText($status)
    {
        return $this->status->getOptionText($status);
    }
    protected function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}

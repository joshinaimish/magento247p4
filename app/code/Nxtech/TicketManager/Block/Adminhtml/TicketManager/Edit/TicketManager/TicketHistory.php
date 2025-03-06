<?php
namespace Nxtech\TicketManager\Block\Adminhtml\TicketManager\Edit\TicketManager;

use Nxtech\TicketManager\Api\TicketManagerRepositoryInterface;
use Magento\User\Model\UserFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
class TicketHistory extends \Magento\Framework\View\Element\Template
{

    const ATTACHMENT_PATH = 'wysiwyg/ticketmanager/';
    /**
     * Block template.
     *
     * @var string
     */
    protected $_template = 'ticket/history.phtml';

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        protected TicketManagerRepositoryInterface $ticketManagerRepository,
        protected \Nxtech\TicketManager\Model\ResourceModel\TicketChat\Collection $ticketChatCollection,
        protected CustomerRepositoryInterface $customerRepositoryInterface,
        protected UserFactory $userFactory,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getTicketDetail()
    {
        $id = $this->getRequest()->getParam('id');
        $ticketData = $this->ticketManagerRepository->get($id);
        return $ticketData;
    }
    public function getTicketHistory($id)
    {
        //$collection = $this->ticketChatRepository->get($id);
        $collection = $this->ticketChatCollection->addFieldToFilter('ticket_id', $id);
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
            $customer = $this->customerRepositoryInterface->getById($customerId);
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
}

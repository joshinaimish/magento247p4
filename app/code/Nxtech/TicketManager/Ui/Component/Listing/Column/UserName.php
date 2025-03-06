<?php
namespace Nxtech\TicketManager\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\User\Model\UserFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
class UserName extends Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UserFactory $userFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected UserFactory $userFactory,
        protected CustomerRepositoryInterface $customerRepositoryInterface,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->userFactory = $userFactory;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            //$rowData = $dataSource['data']['items'];
            foreach ($dataSource['data']['items'] as &$item) {
                if ($item[$fieldName] != '') {
                    $userType = $item['user_type'];
                    $adminName = $this->getAdminName($item[$fieldName]);
                    $item[$fieldName] = $userType . ' - ' . $adminName;
                }
            }
        }
        return $dataSource;
    }

    /**
     * @param $userId
     * @return string
     */
    private function getAdminName($adminId)
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
}
<?php
namespace Nxtech\Blog\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Customer implements ResolverInterface
{
    protected $customerRepository;
    protected $orderRepository;
    protected $searchCriteriaBuilder;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $customerId = $context->getUserId(); // Get logged-in customer ID

        if (!$customerId) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Customer is not logged in.'));
        }

        // Fetch customer data
        $customer = $this->customerRepository->getById($customerId);

        // Fetch orders
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('customer_id', $customerId)
            ->create();

        $orders = $this->orderRepository->getList($searchCriteria)->getItems();

        $orderData = [];
        foreach ($orders as $order) {
            $orderData[] = [
                'order_id' => (int) $order->getEntityId(),
                'increment_id' => $order->getIncrementId(),
                'total' => (float) $order->getGrandTotal(),
                'subtotal' => (float) $order->getSubtotal(),
                'billing_address' => ['billing_address_id' => (int) $order->getBillingAddressId()],
                'shipping_address' => ['shipping_address_id' => (int) $order->getShippingAddressId()]
            ];
        }

        return [
            [
                'firstname' => $customer->getFirstname(),
                'lastname' => $customer->getLastname(),
                'email' => $customer->getEmail(),
                'orders' => $orderData
            ]
        ];
    }
}

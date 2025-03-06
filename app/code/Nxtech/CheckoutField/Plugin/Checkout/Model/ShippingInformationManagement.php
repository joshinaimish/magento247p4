<?php
namespace Nxtech\CheckoutField\Plugin\Checkout\Model;

use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use Nxtech\CheckoutField\Model\Source\Course;

class ShippingInformationManagement
{
    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    protected $_session;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepositoryInterface;

    /**
     * ShippingInformationManagement constructor.
     *
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        \Magento\Customer\Model\Session $session,
        AddressRepositoryInterface $addressRepositoryInterface,
        public Course $course
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->_session = $session;
        $this->addressRepositoryInterface = $addressRepositoryInterface;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getExtensionAttributes();
        $shippingAddress = $addressInformation->getShippingAddress();
        $billingAddress = $addressInformation->getBillingAddress();
        $cusAddId = $shippingAddress->getData('customer_address_id');

        $shippingAddressExtensionAttributes = $shippingAddress->getExtensionAttributes();
        $shippingAddressCustomAttributes = $shippingAddress->getCustomAttributes();

        //print_r($extAttributes);
        //echo $extAttributes->getDeliveryNote();
        //print_r($shippingAddressCustomAttributes);
        //exit('stop3210');
        //echo $deliveryNote = $shippingAddressCustomAttributes->getDeliveryNote();
        //echo $course = $shippingAddressCustomAttributes->getCourse();
        //exit;

        if ($this->_session->isLoggedIn() && $cusAddId) {
            $customerAddressData = $this->getCustomerAddress($cusAddId);
            $deliveryNote = ($customerAddressData->getCustomAttribute('delivery_note')) ? $customerAddressData->getCustomAttribute('delivery_note')->getValue() : '';
            $course = ($customerAddressData->getCustomAttribute('course')) ? $customerAddressData->getCustomAttribute('course')->getValue() : '';

            $shippingAddress->setDeliveryNote($deliveryNote);
            $billingAddress->setDeliveryNote($deliveryNote);
            $shippingAddress->setCourse($course);
            $billingAddress->setCourse($course);
        } else {
            $deliveryNote = $extAttributes->getDeliveryNote();
            $course = $extAttributes->getCourse();
            $shippingAddress->setDeliveryNote($deliveryNote);
            $billingAddress->setDeliveryNote('billing delivery note');
            $shippingAddress->setCourse($course);
            $billingAddress->setCourse($course);
            /* if ($shippingAddressExtensionAttributes) {
                $deliveryNote = $shippingAddressExtensionAttributes->getDeliveryNote();
                $course = $shippingAddressExtensionAttributes->getCourse();
                $shippingAddress->setDeliveryNote($deliveryNote);
                $billingAddress->setDeliveryNote($deliveryNote);
                $shippingAddress->setCourse($course);
                $billingAddress->setCourse($course);
            } elseif ($shippingAddressCustomAttributes) {
                $deliveryNote = $shippingAddressCustomAttributes->getDeliveryNote();
                $course = $shippingAddressCustomAttributes->getCourse();
                $shippingAddress->setDeliveryNote($deliveryNote);
                $billingAddress->setDeliveryNote($deliveryNote);
                $shippingAddress->setCourse($course);
                $billingAddress->setCourse($course);
            } else {
                $deliveryNote = $extAttributes->getDeliveryNote();
                $course = $extAttributes->getCourse();
                $shippingAddress->setDeliveryNote($deliveryNote);
                $billingAddress->setDeliveryNote($deliveryNote);
                $shippingAddress->setCourse($course);
                $billingAddress->setCourse($course);
            } */
        }
    }
    public function getCustomerAddress($addressId)
    {
        $addressRepository = $this->addressRepositoryInterface->getById($addressId);
        return $addressRepository;
    }
}
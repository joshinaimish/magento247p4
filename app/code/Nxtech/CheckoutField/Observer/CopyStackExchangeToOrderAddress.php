<?php
namespace Nxtech\CheckoutField\Observer;

use Magento\Framework\DataObject\Copy as ObjectCopyService;
use Magento\Framework\Event\ObserverInterface;

class CopyStackExchangeToOrderAddress implements ObserverInterface
{
    /**
     * @var ObjectCopyService
     */
    private ObjectCopyService $objectCopyService;

    public function __construct(
        ObjectCopyService $objectCopyService
    ) {
        $this->objectCopyService = $objectCopyService;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /* @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_convert_quote_address',
            'to_order_address',
            $quote->getShippingAddress(),
            $order->getShippingAddress()
        );
        $this->objectCopyService->copyFieldsetToTarget(
            'sales_convert_quote_address',
            'to_order_address',
            $quote->getBillingAddress(),
            $order->getBillingAddress()
        );
        return $this;
    }
}
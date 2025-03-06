<?php
namespace Nxtech\CheckoutField\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class SaveDeliveryNoteToOrderObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $quote = $observer->getQuote();
        $order = $observer->getOrder();
        //$deliveryNote = $quote->getShippingAddress()->getExtensionAttributes()->getDeliveryNote();
        $deliveryNote = $quote->getShippingAddress()->getDeliveryNote();
        $billingDeliveryNote = $quote->getBillingAddress()->getDeliveryNote();

        //if ($deliveryNote = $quote->getShippingAddress()->getExtensionAttributes()->getDeliveryNote()) {
        //$order->getShippingAddress()->getExtensionAttributes()->setDeliveryNote("tetst32");
        //$order->getBillingAddress()->getExtensionAttributes()->setDeliveryNote("tetst32");
        if ($deliveryNote = $quote->getShippingAddress()->getDeliveryNote()) {
            $order->getShippingAddress()->setDeliveryNote($deliveryNote);
            //$order->getBillingAddress()->setDeliveryNote($deliveryNote);
            $order->setDeliveryNote($deliveryNote);
        }
        if ($billingDeliveryNote) {
            $order->getBillingAddress()->setDeliveryNote($billingDeliveryNote);
            $order->setDeliveryNote($billingDeliveryNote);
        }
        if ($course = $quote->getShippingAddress()->getCourse()) {
            $order->getShippingAddress()->setCourse($course);
            $order->getBillingAddress()->setCourse($course);
            $order->setCourse($course);
        }
    }
}
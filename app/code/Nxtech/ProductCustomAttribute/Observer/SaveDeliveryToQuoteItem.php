<?php
namespace Nxtech\ProductCustomAttribute\Observer;

use Psr\Log\LoggerInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Quote\Model\Quote\Item;

class SaveDeliveryToQuoteItem implements ObserverInterface
{
    public function __construct(
        protected LoggerInterface $logger,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \Magento\Framework\View\LayoutInterface $layout,
        protected \Magento\Framework\App\RequestInterface $request,
        protected \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
    }

    public function execute(Observer $observer)
    {
        $quoteItem = $observer->getQuoteItem();
        $product = $quoteItem->getProduct();
        // Retrieve custom field value from the product
        $delivery = $product->getData('delivery');
        // Save the custom field value in quote_item
        $quoteItem->setDelivery($delivery);

        $deliveryText = $product->getAttributeText('delivery');
        //$postValue = $this->request->getParams();
        if (isset($delivery) && $delivery) {


            $deliveryData = [];
            $deliveryData[] = [
                'label' => 'Delivery',
                'value' => $deliveryText,
            ];

            if (count($deliveryData) > 0) {
                $quoteItem->addOption([
                    'product_id' => $quoteItem->getProductId(),
                    'code' => 'additional_options',
                    'value' => $this->serializer->serialize($deliveryData),
                ]);
            }
        }
    }
}
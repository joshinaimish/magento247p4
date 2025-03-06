<?php

namespace Nxtech\MessageQueueExample\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class ProductDeleteObserver implements ObserverInterface
{
    const TOPIC_NAME = 'asynchronous.product.delete';
    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected \Magento\Framework\Json\Helper\Data $jsonHelper,
        public \Magento\Framework\MessageQueue\PublisherInterface $publisher,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * Execute observer on product delete
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();

        if ($product) {
            $productId = (int) $product->getId();
            // Log the product delete event
            $this->logger->info("Product deleted from observer: ID " . $productId);
            $messageData = ['product_id' => $productId];
            // Publish message to queue
            //$this->productDeletePublisher->publish($messageData);
            $this->publisher->publish(
                self::TOPIC_NAME,
                $this->jsonHelper->jsonEncode($messageData)
            );
        }
    }
}

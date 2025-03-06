<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Nxtech\MessageQueueExample\Model;
use Psr\Log\LoggerInterface;

class GetDataManagement implements \Nxtech\MessageQueueExample\Api\GetDataManagementInterface
{

    const TOPIC_NAME = 'asynchronous.product.delete';
    public function __construct(
        public \Magento\Framework\MessageQueue\PublisherInterface $publisher,
        protected \Magento\Framework\Json\Helper\Data $jsonHelper,
        protected LoggerInterface $logger
    ) {
    }
    /**
     * {@inheritdoc}
     */
    public function getGetData($sku): string
    {
        $sku = (int) $sku;
        $messageData = ['product_id' => $sku];
        $this->logger->info("API Called for ID: " . $sku);
        $this->publisher->publish(self::TOPIC_NAME, $this->jsonHelper->jsonEncode($messageData));
        $this->logger->info("Called Publisher After ");
        return "Called Publisher" . $sku;
    }
}

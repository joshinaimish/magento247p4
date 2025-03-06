<?php
namespace Nxtech\MessageQueueExample\Model;

use Magento\Framework\MessageQueue\ConsumerConfiguration;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;
/**
 * Class Consumer used to process OperationInterface messages.
 */
class ProductDeleteHandler extends ConsumerConfiguration
{
    public function __construct(
        protected \Magento\Framework\Json\Helper\Data $jsonHelper,
        protected \Magento\Framework\Message\ManagerInterface $messageManager,
        protected \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        protected ScopeConfigInterface $scopeConfig,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * consumer process start
     * @param string $message
     * @return string
     */
    public function process($message)
    {
        try {
            $data = $this->jsonHelper->jsonDecode($message, true);

            if (!isset($data['product_id'])) {
                $this->logger->error("Invalid message received: " . $message);
                return false;
            }

            $productId = (int) $data['product_id'];
            $this->logger->info("Processing product deletion for ID: " . $productId);
            return "called Consumer";

        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }
}
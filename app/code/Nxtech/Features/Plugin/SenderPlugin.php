<?php
// app/code/Nxtech/Features/Plugin/SenderPlugin.php
namespace Nxtech\Features\Plugin;

class SenderPlugin
{
    public function beforePrepareTemplate(
        \Cart2Quote\Quotation\Model\Quote\Email\AbstractSender $subject,
        \Cart2Quote\Quotation\Model\Quote $quote,
        $internalEmail = null
    ) {
        exit('called');
        // Custom logic before the prepareTemplate method
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class);
        $logger->debug('Custom prepareTemplate before plugin called.');
    }
}